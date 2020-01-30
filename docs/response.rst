.. _response:

========
Response
========

For processing response from GPWebPay your are need :ref:`service.response_factory` and :ref:`service.response_provider`

.. _response.create:

Create response
###############

.. code-block:: php

	$params = $_GET; // or $_POST depends on how you send request
	$response = $responseFactory->create($params);

Now you are have two options.

.. _response.manual:

**Processing by your code.**

.. code-block:: php

	// verify response signatures
	if (!$provider->verifyPaymentResponse($response)) {
		// invalid verification
	}
	// success verification
	if ($response->hasError()) {
		// here is you code for processing response error
	}
	// here is you code for processing response


**Or use ResponseProvider and callbacks**

.. code-block:: php

	// success callbacks
	$provider
		->addOnSuccess(
			static function (ResponseInterface $response) {
				// here is your code for processing response
			}
		)
		->addOnSuccess(
			static function (ResponseInterface $response) {
				// here is your another code for processing response
			}
		);

	// error callback
	$provider->addOnError(
		static function (GPWebPayException $exception, ResponseInterface $response) {
			// here is you code for processing error
		}
	);

	// and next step is call
	$provider->provide($response);


.. _response.params:

Parameters
##########

.. _response.params.resultText:

ResultText
----------

A text description of the error identified by a combination of PRCODE and SRCODE.
The contents are coded using the Windows Central European (Code Page 1250).

:return: ``string``

.. code-block:: php

	$response->getResultText();

.. _response.params.prcode:

PrCode
------

Primary code. For details, see “List of return codes in GPWebPay doc”.

:return: ``int``

.. code-block:: php

	$response->getPrcode();


.. _response.params.srcode:

SrCode
----------

Secondary code. For details, see “List of return codes in GPWebPay doc”.

:return: ``int``

.. code-block:: php

	$response->getSrcode();


.. _response.params.ordernumber:

OrderNumber
-----------

Contents of the field from the request.

:return: ``string``

.. code-block:: php

	$response->getOrderNumber();


.. _response.params.merordernumber:

MerOrderNumber
--------------

Contents of the field from the request, if included.

:return: ``string`` | ``null``

.. code-block:: php

	$response->getMerOrderNumber();


.. _response.params.userparam:

UserParam1
----------

Hash numbers of the payment card. Hash is a unique value for each and every card and merchant – that is if the payment is made by the same card at the same merchant, the resulting hash is identical, if the same card is used at another merchant, there is another hash.

:return: ``string`` | ``null``

.. note:: Only if the merchant has this functionality enabled

.. code-block:: php

	$response->getUserParam1();

.. _response.params.md:

Md
--

Hash numbers of the payment card. Hash is a unique value for each and every card and merchant – that is if the payment is made by the same card at the same merchant, the resulting hash is identical, if the same card is used at another merchant, there is another hash.

.. note:: GPWebPay core use this field to store information about used gateway.

	So method ``$response->getParam(string $paramName)`` return value contain gateway info.

:return: ``string`` | ``null``

.. code-block:: php

	$response->getMd();


.. _response.params.digest:

Digest and Digest1
------------------

*Digest* is a check signature of the string generated as a concatenation of all the fields sent in the given order

*Digest1* is same as *Digest* but (without the DIGEST field) and on the top of that also the MERCHANTNUMBER field (the field is not sent, the merchant has to know it, the field is added to the end of the string).

:return: ``string``

.. code-block:: php

	$response->getDigest();
	$response->getDigest1();
