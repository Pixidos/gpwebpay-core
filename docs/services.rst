.. _service:

========
Services
========

After creating the :ref:`configuration`, create the services below.

.. _service.signer_provider:

SignerProvider
--------------

``SignerProvider`` supplies a ``Signer`` for each configured gateway.

.. code-block:: php

	use Pixidos\GPWebPay\Signer\SignerFactory;
	use Pixidos\GPWebPay\Signer\SignerProvider;

	$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());

.. _service.request_factory:

RequestFactory
---------------

``RequestFactory`` creates a signed ``Request`` from an :ref:`request.operation`.

.. code-block:: php

	use Pixidos\GPWebPay\Factory\RequestFactory;
	$requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);

.. _service.response_factory:

ResponseFactory
----------------

``ResponseFactory`` creates a ``Response`` from the received parameters. It does not verify its signatures.

.. code-block:: php

	use Pixidos\GPWebPay\Factory\ResponseFactory;
	$responseFactory = new ResponseFactory($config->getPaymentConfigProvider());



.. _service.response_provider:

ResponseProvider
----------------

``ResponseProvider`` verifies both response signatures and dispatches success or error callbacks.

.. code-block:: php

	use Pixidos\GPWebPay\ResponseProvider;

	$provider = new ResponseProvider(
		$config->getPaymentConfigProvider(), $signerProvider
	);
