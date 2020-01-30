.. _request:

=====================
Operation and Request
=====================

For creating request to GPWebPay API you are need create two objects. ``Operation`` and ``Request``

.. _request.operation:

The Operation
#############

For basic operation must create :ref:`request.params.order_number`, :ref:`request.params.amount`
and :ref:`request.params.currency` objects with values of your payment order.

.. code-block:: php

	use Pixidos\GPWebPay\Data\Operation;

	$operation = new Operation(
		$orderNumber,
		$amount,
		$currency,
	   'czk', // optional, if you leave empty so will be use default
		new ResponseUrl('http://example.com/proccess-gpw-response') // optional when you setup in config
	);

more :ref:`request.params` you can simple add by method `addParam(IParam $param)`

For example:

.. code-block:: php

	$operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));

.. _request.request:

Request and Rendering
#####################

Request you create by :ref:`service.request_factory`

.. code-block:: php

	$request = $requestFactory->create($operation);

And render the payment button

.. code-block:: php

	echo sprintf('<a href="%s">This is pay link</a>', $request->getRequestUrl());

or you can render ``form`` for ``post`` method

.. code-block:: php

	<form action="<?= $request->getRequestUrl(true) ?>">
		<?php
		/** @var IParam $param */
		foreach ($request->getParams() as $param) {
			echo sprintf('<input type=hidden value="%s" name="%s">%s', $param->getValue(), $param->getParamName(), "\n\r");
		}
		?>
		<input type="submit" value="Pay">
	</form>


.. _request.params:

Parameters
################

.. _request.params.order_number:

OrderNumber
-----------

Ordinal number of the order. Every request from a merchant has to contain a unique order number.

.. warning:: Is not your order number! For specify you order number use :ref:`request.params.merOrderNum` parameter

You are have two ways how specify this.

.. code-block:: php

	// you can create on time base on any other integer unique generator.
	$orderNumber = new OrderNumber(time());


.. _request.params.amount:

Amount
------

Because the amount is the smallest units of the relevant currency For CZK = in hellers, for EUR = in cents.

You are have two ways how specify this.

.. code-block:: php

	// The conversion will make Amount self
	$amount = new Amount(1000.00);
	// or create the conversion by yourself
	$amount = new Amount(100000, false);



.. _request.params.currency:

Currency
--------

Currency identifier according to ISO 4217 (see Addendum ISO 4217).

You are simple create this, because in class
``Pixidos\GPWebPay\Enum\Currency`` you are have all constants with ISO code
and methods for create the enum.

.. code-block:: php

	use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;

	$currency = new Currency(CurrencyEnum::CZK())


.. _request.params.merOrderNum:

MerOrderNum
-----------

Order identification for the merchant. If not specified, the :ref:`request.params.order_number` value is used

.. code-block:: php

	use Pixidos\GPWebPay\Param\MerOrderNum;

	$merOrderNum = new MerOrderNum(123455);

