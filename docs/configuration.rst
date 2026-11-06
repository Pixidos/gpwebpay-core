.. _configuration:

=============
Configuration
=============

You need a configuration for every gateway. The library supports a single gateway or several gateways,
for example one per currency, each with its own merchant number and keys.

.. warning::
	Never commit private keys, passphrases or merchant credentials to version control, and keep key files
	outside the web root. Load them from your secret storage or environment, as the :ref:`example` does.


.. _configuration.attributes:

Attributes
##########

.. _configuration.attributes.privateKey:

The `privateKey` attribute
--------------------------

Absolute path to your private key ``pem`` file, generated in the GP webpay portal.

:Type: ``string``

.. _configuration.attributes.privateKeyPassphrase:

The `privateKeyPassphrase` attribute
------------------------------------

The passphrase for your private key.

:Type: ``string``

.. _configuration.attributes.publicKey:

The `publicKey` attribute
-------------------------

Absolute path to the GP webpay public key ``pem`` file, downloaded from the GP webpay portal.
It verifies the signatures of responses.

:Type: ``string``

.. _configuration.attributes.url:

The `url` attribute
-------------------

Absolute URL of the gateway. GP webpay HTTP API 1.19 lists:

* test environment: ``https://test.3dsecure.gpwebpay.com/pgw/order.do``
* production: ``https://3dsecure.gpwebpay.com/pgw/order.do``

:Type: ``string``

.. note::
	Use the address your provider gives you. Older integrations may use another path, such as
	``/unicredit/order.do``; this library does not change the configured URL.

.. _configuration.attributes.depositFlag:

The `depositFlag` attribute
---------------------------

| Specifies whether the payment should be captured automatically.
| ``0`` = authorize without immediate capture
| ``1`` = request capture

:Type: ``int``



.. _configuration.attributes.merchantNumber:

The `merchantNumber` attribute
------------------------------

Your merchant number, assigned by the provider.

:Type: ``string``

.. _configuration.attributes.responseUrl:

The `responseUrl` attribute
---------------------------

Absolute URL of your page that receives the response, see :ref:`response`.

:Type: ``string``

Optional. Each gateway can have its own URL; a request uses the URL of its gateway unless the operation sets
a ``ResponseUrl``. If neither is set, creating the request fails.

.. warning:: GP webpay may refuse addresses with query parameters (after ``?``) for security reasons.
	Test the real address in the test environment.

.. _configuration.gateways:

Gateway keys
############

Gateway keys are case-insensitive and stored in lowercase (``CZK`` and ``czk`` are the same gateway). The key is sent
in MD, so it must consist of printable ASCII characters without ``|`` and ``#``.

.. _configuration.example:

Examples
########

.. _configuration.example.single_gateway:

Single gateway
--------------

.. code-block:: php

	use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
	use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;

	$configFactory = new ConfigFactory(new PaymentConfigFactory());

	$config = $configFactory->create(
		[
			ConfigFactory::PRIVATE_KEY => '/path/outside/webroot/gpwebpay-private-key.pem',
			ConfigFactory::PRIVATE_KEY_PASSPHRASE => $privateKeyPassphrase, // from your secret storage
			ConfigFactory::PUBLIC_KEY => '/path/outside/webroot/gpwebpay-public-key.pem',
			ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/pgw/order.do',
			ConfigFactory::MERCHANT_NUMBER => $merchantNumber,
			ConfigFactory::DEPOSIT_FLAG => 1,
			ConfigFactory::RESPONSE_URL => 'https://shop.example/payment/gpwebpay-response',
		]
	);


.. _configuration.example.multiple_gateways:

Multiple gateways
-----------------

.. code-block:: php

	use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
	use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;

	$configFactory = new ConfigFactory(new PaymentConfigFactory());

	$config = $configFactory->create(
		[
			'czk' => [
				ConfigFactory::PRIVATE_KEY => '/path/outside/webroot/gpwebpay-czk-private-key.pem',
				ConfigFactory::PRIVATE_KEY_PASSPHRASE => $czkPassphrase,
				ConfigFactory::PUBLIC_KEY => '/path/outside/webroot/gpwebpay-public-key.pem',
				ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/pgw/order.do',
				ConfigFactory::MERCHANT_NUMBER => $czkMerchantNumber,
				ConfigFactory::DEPOSIT_FLAG => 1,
				ConfigFactory::RESPONSE_URL => 'https://shop.example/payment/gpwebpay-response',
			],
			'eur' => [
				ConfigFactory::PRIVATE_KEY => '/path/outside/webroot/gpwebpay-eur-private-key.pem',
				ConfigFactory::PRIVATE_KEY_PASSPHRASE => $eurPassphrase,
				ConfigFactory::PUBLIC_KEY => '/path/outside/webroot/gpwebpay-public-key.pem',
				ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/pgw/order.do',
				ConfigFactory::MERCHANT_NUMBER => $eurMerchantNumber,
				ConfigFactory::DEPOSIT_FLAG => 1,
				ConfigFactory::RESPONSE_URL => 'https://shop.example/payment/gpwebpay-eur-response',
			],
		],
		'czk' // the default gateway
	);
