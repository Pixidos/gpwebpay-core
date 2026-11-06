.. _request:

=====================
Operation and Request
=====================

To send a payment request to GP webpay, create two objects: an ``Operation`` with the payment data
and a signed ``Request`` created from it.

.. _request.operation:

The operation
#############

A basic operation needs an :ref:`request.params.order_number`, an :ref:`request.params.amount_in_pennies`
and a :ref:`request.params.currency`.

.. code-block:: php

    use Pixidos\GPWebPay\Data\Operation;
    use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
    use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
    use Pixidos\GPWebPay\Param\AmountInPennies;
    use Pixidos\GPWebPay\Param\Currency;
    use Pixidos\GPWebPay\Param\OrderNumber;
    use Pixidos\GPWebPay\Param\ResponseUrl;

    $operation = new Operation(
        orderNumber: new OrderNumber($paymentAttemptId),
        amount: new AmountInPennies(129900), // 1 299.00 CZK in hellers
        currency: new Currency(CurrencyEnum::CZK()),
        gateway: 'czk', // optional, the default gateway is used when omitted (since 2.4.0)
        responseUrl: new ResponseUrl('https://shop.example/payment/gpwebpay-response'), // optional when configured
        operation: OperationEnum::CREATE_ORDER() // optional, CREATE_ORDER is the default
    );

.. note::
    With several gateways, omit **gateway:** to use the default gateway, or name the configured gateway.
    Gateway names are case-insensitive. The request is signed with the keys, merchant number and response URL
    of the selected gateway.

.. note::
    ``CARD_VERIFICATION`` and ``FINALIZE_ORDER`` can be selected, but the library sends AMOUNT, CURRENCY and
    DEPOSITFLAG for every operation. Verify these operations with your provider in the test environment before using them.

Add more :ref:`request.params` with ``addParam(IParam $param)``, for example:

.. code-block:: php

    $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));

.. _request.request:

Create and render a request
###########################

Create the request with the :ref:`service.request_factory`. It adds the merchant number, the deposit flag and the
response URL of the selected gateway and signs all fields except LANG.

.. code-block:: php

    $request = $requestFactory->create($operation);

GP webpay recommends sending the request with ``POST``: it has no URL length limit and keeps UTF-8 characters in
ADDINFO intact. ``getRequestUrl(true)`` returns the gateway address for the form action and ``getParams()`` returns the
signed fields as ``name => value`` strings. Escape every value:

.. code-block:: php

    $escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    echo '<form method="post" action="' . $escape($request->getRequestUrl(true)) . '">';
    foreach ($request->getParams() as $name => $value) {
        echo '<input type="hidden" name="' . $escape($name) . '" value="' . $escape($value) . '">';
    }
    echo '<button type="submit">Pay</button>';
    echo '</form>';

A payment link (``GET``) is also possible for short requests:

.. code-block:: php

    echo '<a href="' . $escape($request->getRequestUrl()) . '">Pay</a>';


.. _request.params:

Parameters
##########

.. _request.params.order_number:

OrderNumber
-----------

The payment request number. GP webpay requires it to be unique across all requests from the merchant:
a number with at most 15 digits that is never reused, not even for another attempt to pay the same order.

.. warning:: It is not your order number. Send your order number in :ref:`request.params.merOrderNum`.

Before redirecting the customer, save a payment attempt (order, gateway, amount, currency, status ``pending``) and use
its unique numeric ID, for example an ``AUTO_INCREMENT`` key, as the order number. Do not use ``time()`` or a random
number: two customers can start a payment in the same second, and random numbers can collide.

.. code-block:: php

    $orderNumber = new OrderNumber($paymentAttemptId);

The response contains the same ORDERNUMBER, so you can find the payment attempt, its order and the amount you requested.


.. _request.params.amount_in_pennies:

AmountInPennies
---------------

Amount in the smallest unit of the currency: hellers for CZK, cents for EUR. Use integers from your database or a
money library; never compute prices with floats.

.. code-block:: php

    $amount = new AmountInPennies(100000); // 1 000.00 CZK or EUR

The value must be from 0 to 999 999 999 999 999 (15 digits). Whether 0 is accepted depends on the operation and on
your provider.

.. _request.params.amount:

Amount
------

.. warning::
    ``Amount`` is **deprecated**. Use :ref:`request.params.amount_in_pennies`.

``Amount`` converts a float in the main currency unit to the smallest unit. For example, ``new Amount(19.99)`` sends
exactly 1999; older versions could send one heller less (1998). Values must be positive, represent at most two decimal
places and fit within 15 digits in the smallest unit. With conversion disabled, the input must represent a whole
number of smallest units.

Very small rounding errors from short calculations such as ``0.1 + 0.2`` are tolerated. Larger errors are rejected;
at large amounts, only floats that exactly match a whole number of smallest units are accepted. A float cannot retain
every digit of an arbitrarily precise decimal input, so the constructor cannot detect precision already lost before
the call. Use :ref:`request.params.amount_in_pennies` to avoid floats altogether.

.. code-block:: php

    $amount = new Amount(1000.00);       // 100000
    $amount = new Amount(100000, false); // already in the smallest unit: 100000


.. _request.params.currency:

Currency
--------

Currency identifier according to ISO 4217. ``Pixidos\GPWebPay\Enum\Currency`` contains all codes.

.. code-block:: php

    use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;

    $currency = new Currency(CurrencyEnum::CZK());


.. _request.params.merOrderNum:

MerOrderNum
-----------

Your order number. It may appear on the bank statement; GP webpay passes at most 16 digits to the provider
and banks show fewer. If it is not specified, the :ref:`request.params.order_number` is used.

.. code-block:: php

    use Pixidos\GPWebPay\Param\MerOrderNum;

    $merOrderNum = new MerOrderNum(2026000123);


.. _request.params.md:

Md
--

Merchant data returned unchanged in the response. ASCII characters 0x20 to 0x7E only, at most 255 characters
including the gateway prefix, and no personal data.

The library uses MD to remember the gateway: an operation for gateway ``czk`` sends ``MD=czk``. Data you add are sent as
``czk|<data>`` (``|<data>`` with the default gateway). :ref:`response.params.md` returns the payload without any
``#ID=…`` or ``#IDPP=…`` suffix appended by the provider.

.. code-block:: php

    use Pixidos\GPWebPay\Param\Md;

    $operation->addParam(new Md('cart-42'));


.. _request.params.paymethods:

PayMethods and PayMethod
------------------------

``PayMethods`` (PAYMETHODS) lists the payment methods offered on the payment page. ``PayMethod`` (PAYMETHOD) selects
the preferred method. ``Pixidos\GPWebPay\Enum\PayMethod`` contains the values of GP webpay HTTP API 1.19, appendix 5:
cards, Google Pay, Apple Pay, PayPal, Click to Pay, GPE alternative payment methods (``APM_*``) and Twisto
(``TWISTO_PAY_IN_30_DAYS``, ``TWISTO_PAY_IN_THIRDS``). Your provider decides which methods you may use.

.. code-block:: php

    use Pixidos\GPWebPay\Enum\PayMethod;
    use Pixidos\GPWebPay\Param\PayMethods;

    $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::APPLE_PAY(), PayMethod::TWISTO_PAY_IN_30_DAYS()));

Values that GP webpay removed, such as ``MASTERPASS`` and ``BANK_*`` (Platba z účtu), are deprecated and kept only for
compatibility. ``PLATBA24``, ``SOFORT``, ``EPS``, ``PAYSAFECARD``, ``SEPA_DIRECT_DEBIT`` and ``KLARNA`` are being phased
out by the provider.

.. warning::
    ``DisablePayMethod`` (DISABLEPAYMETHOD) is **deprecated**: GP webpay removed the field in HTTP API 1.16.
    List the allowed methods with ``PayMethods`` instead.


.. _request.params.addinfo:

AddInfo
-------

ADDINFO is an XML document with additional data: cardholder data for 3-D Secure and fraud detection, shopping cart,
wallet settings, submerchant data and more. ``AddInfo`` sends the XML you give it unchanged and signed; it neither
builds nor validates it. Validate it against the ADDINFO XSD from the GP webpay portal (current template version 5.0).

The card schemes require the cardholder name and an e-mail address or a phone number (with its country code) for every
card payment. Send only real data: when a value is not available, leave the element out instead of sending an empty or
invented value.

.. code-block:: php

    use Pixidos\GPWebPay\Param\AddInfo;

    $xml = static fn (string $value): string => htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');

    $operation->addParam(new AddInfo(
        '<additionalInfoRequest xmlns="http://gpe.cz/gpwebpay/additionalInfo/request" version="5.0">'
        . '<cardholderInfo><cardholderDetails>'
        . '<name>' . $xml($customerName) . '</name>'      // ASCII characters only
        . '<email>' . $xml($customerEmail) . '</email>'
        . '</cardholderDetails></cardholderInfo>'
        . '</additionalInfoRequest>'
    ));

Do not put line breaks or spaces between the elements; browsers may change them and the signature then fails.
Newer elements such as ``paymentGatewayData`` or ``submerchantInfo/merchantCountryOfOrigin`` are sent the same way.


.. _request.params.token:

UserParam, Token, FastToken, FastPayId
--------------------------------------

``UserParam`` (USERPARAM1) identifies registration payments for recurring payments and stored cards (values such as ``R``,
``T`` or ``S``, see the GP webpay documentation). ``Token``, ``FastToken`` and ``FastPayId`` use a card stored by an
earlier payment. Since HTTP API 1.17 the response returns the card token in TOKEN, see :ref:`response.params.token`.


.. _request.params.vrcode:

VRCode and PanPattern
---------------------

``VRCode`` (VRCODE) sends a verification code to the authorization centre; ``PanPattern`` (PANPATTERN) restricts the
card numbers accepted on the payment page. Both fields are part of DIGEST. Older versions of this library sent them
without signing them, so the signature did not match what GP webpay expects.
