.. _response:

========
Response
========

To process a response from GP webpay, use the :ref:`service.response_factory` and the
:ref:`service.response_provider`.

.. _response.create:

Create a response
#################

GP webpay sends the result through the customer's browser to your response URL, either by a redirect (``GET``) or by an
automatically submitted form (``POST``). The provider chooses the method according to the services used, so your
response URL must accept both. Read the data of the actual request method; never merge ``$_GET`` and ``$_POST``.

.. code-block:: php

	use Pixidos\GPWebPay\Exceptions\InvalidResponseException;

	$params = 'POST' === ($_SERVER['REQUEST_METHOD'] ?? 'GET') ? $_POST : $_GET;

	try {
		$response = $responseFactory->create($params);
	} catch (InvalidResponseException $exception) {
		// not a GP webpay response for this shop: a missing or malformed field, or an unknown gateway in MD
		http_response_code(400);
		return;
	}

The factory ignores fields that are not part of a GP webpay response. It does **not** verify the signatures: until
the response is verified, every value in it comes from the browser and may be forged.

Then choose one of two ways of processing it.

.. _response.provider:

**ResponseProvider and callbacks (recommended)**

``provide()`` verifies DIGEST and DIGEST1 first. Success callbacks run only for an authentic response with PRCODE and
SRCODE equal to 0; otherwise the error callbacks receive the reason. Error callbacks must accept
``GPWebPayException``, the common parent of the signature and payment-result exceptions.

.. code-block:: php

	use Pixidos\GPWebPay\Data\ResponseInterface;
	use Pixidos\GPWebPay\Exceptions\GPWebPayException;
	use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;

	$provider->addOnSuccess(
		static function (ResponseInterface $response): void {
			// authentic and paid: mark the payment attempt paid, see "Processing a payment" below
		}
	);

	$provider->addOnError(
		static function (GPWebPayException $exception, ResponseInterface $response): void {
			if ($exception instanceof GPWebPayResultException) {
				// authentic, but the payment failed: $exception->getPrcode(), getSrcode(), translate('en')
				return;
			}
			// SignerException: not authentic; change nothing and reject the request
		}
	);

	$provider->provide($response);

Without any error callback, ``provide()`` throws the exception instead.

.. _response.manual:

**Manual processing**

Stop on every failure; never continue after a failed check.

.. code-block:: php

	if (!$provider->verifyPaymentResponse($response)) {
		// not authentic: change nothing, log it and reject the request
		http_response_code(400);
		return;
	}

	if ($response->hasError()) {
		// authentic, but the payment failed: mark the payment attempt failed and stop
		return;
	}

	// authentic and paid


.. _response.processing:

Processing a payment
####################

The examples assume a standard payment with ``depositFlag=1``. With ``depositFlag=0``, a successful response
confirms authorization; arrange capture through your provider before treating it as a captured payment.
Handle other operation types according to their provider contract.

The library does not store payments. Your application must:

* find the payment attempt by ORDERNUMBER (``$response->getOrderNumber()``) and check that it belongs to the gateway
  ``$response->getGatewayKey()``; reject unknown attempts;
* take the amount and currency from your payment attempt. The response does not repeat them; they were signed
  in your request for this ORDERNUMBER;
* change the state atomically and only once, for example
  ``UPDATE payment_attempt SET status = 'paid' WHERE id = ? AND status = 'pending'``. The same signed response can
  arrive again (page refresh, back button, replay) and must not fulfil the order twice;
* not rely on the browser returning at all. If no response arrives within about 15 minutes, ask GP webpay for the
  payment state through its Web Services (``processPaymentStatus``, ``processPaymentDetail``).

Exception messages may contain received values. Do not show them unescaped.


.. _response.params:

Parameters
##########

``getParams()`` returns all signed fields exactly as received, in signing order, without DIGEST and DIGEST1.
Fields without a dedicated getter are read from it, for example:

.. code-block:: php

	use Pixidos\GPWebPay\Data\ResponseInterface;
	use Pixidos\GPWebPay\Enum\Param;

	$params = $response->getParams();
	$token = isset($params[Param::TOKEN]) ? $params[Param::TOKEN]->getValue() : null;
	$rrn = isset($params[ResponseInterface::RRN]) ? $params[ResponseInterface::RRN]->getValue() : null;

The library verifies these optional fields: ADDINFO, TOKEN, EXPIRY, ACSRES, ACCODE, PANPATTERN, DAYTOCAPTURE,
TOKENREGSTATUS, ACRC, RRN, PAR, TRACEID, and USERPARAM1 of API versions before 1.17. Some of them are returned only when
your provider enables them.

.. _response.params.resultText:

ResultText
----------

A text description of the result given by PRCODE and SRCODE, without diacritics. Optional; an empty string when absent.

:return: ``string``

.. code-block:: php

	$response->getResultText();

.. _response.params.prcode:

PrCode
------

Primary return code; 0 means OK. See the list of return codes in the GP webpay documentation.

:return: ``int``

.. code-block:: php

	$response->getPrcode();


.. _response.params.srcode:

SrCode
------

Secondary return code with details of the primary code.

:return: ``int``

.. code-block:: php

	$response->getSrcode();


.. _response.params.ordernumber:

OrderNumber
-----------

The ORDERNUMBER from the request.

:return: ``string``

.. code-block:: php

	$response->getOrderNumber();


.. _response.params.merordernumber:

MerOrderNumber
--------------

The MERORDERNUM from the request, if it was sent.

:return: ``string`` | ``null``

.. code-block:: php

	$response->getMerOrderNumber();


.. _response.params.token:

Token and UserParam1
--------------------

Since HTTP API 1.17 GP webpay returns the unique card identifier in TOKEN (read it from ``getParams()``, see above) and
no longer returns USERPARAM1. ``getUserParam1()`` returns USERPARAM1 only for providers that still send it.

:return: ``string`` | ``null``

.. code-block:: php

	$response->getUserParam1();

.. _response.params.md:

Md
--

The merchant data added to the request with ``Md``, without the gateway prefix added by the library or the
``#ID=…`` or ``#IDPP=…`` suffix that GP webpay appends when the provider uses its notification service or the payment
was made by PayPal. ``null`` when the request carried no merchant data.

:return: ``string`` | ``null``

.. code-block:: php

	$response->getMd();

The signed value exactly as received, including the prefix and the suffix:

.. code-block:: php

	$response->getRawMd(); // Pixidos\GPWebPay\Data\Response only

The gateway is selected by the text before the first ``|`` or ``#`` of MD. A response that names a gateway that is not
configured is rejected with ``InvalidResponseException``.


.. _response.params.digest:

Digest and Digest1
------------------

*DIGEST* is the signature of all received fields in the specified order.

*DIGEST1* is the signature of the same fields followed by your MERCHANTNUMBER, which is not sent. It binds the response
to the merchant of the selected gateway.

:return: ``string``

.. code-block:: php

	$response->getDigest();
	$response->getDigest1();
