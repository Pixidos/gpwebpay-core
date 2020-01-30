.. _service:

========
Services
========

After you create the :ref:`configuration` you are need instance a few next services.

.. _service.signer_provider:

SignerProvider
--------------

Signer provider is the services what provide `Signer` for each gateway.

.. code-block:: php

	use Pixidos\GPWebPay\Signer\SignerFactory;
	use Pixidos\GPWebPay\Signer\SignerProvider;

	$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());

.. _service.request_factory:

RequestFactory
---------------

Request factory is helper what provide creating `Request` object from :ref:`request.operation`

.. code-block:: php

	use Pixidos\GPWebPay\Factory\RequestFactory;
	$requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);

.. _service.response_factory:

ResponseFactory
----------------

Service for creating `Response` from received params

.. code-block:: php

	use Pixidos\GPWebPay\Factory\ResponseFactory;
	$responseFactory = new ResponseFactory($config->getPaymentConfigProvider());



.. _service.response_provider:

ResponseProvider
----------------

Is service what validate and can processed `Response`

.. code-block:: php

	use Pixidos\GPWebPay\ResponseProvider;

	$provider = new ResponseProvider(
		$config->getPaymentConfigProvider(), $signerProvider
	);
