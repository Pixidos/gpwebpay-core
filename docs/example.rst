.. _example:

=====================
Example of use
=====================


.. _example.request:
Create Request and Rendering
#############################

Simple example how create request

.. code-block:: php

    <?php

    declare(strict_types=1);

    use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
    use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
    use Pixidos\GPWebPay\Data\Operation;
    use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
    use Pixidos\GPWebPay\Enum\PayMethod;
    use Pixidos\GPWebPay\Factory\RequestFactory;
    use Pixidos\GPWebPay\Factory\ResponseFactory;
    use Pixidos\GPWebPay\Param\AmountInPennies;
    use Pixidos\GPWebPay\Param\Currency;
    use Pixidos\GPWebPay\Param\MerOrderNum;
    use Pixidos\GPWebPay\Param\OrderNumber;
    use Pixidos\GPWebPay\Param\PayMethods;
    use Pixidos\GPWebPay\Signer\SignerFactory;
    use Pixidos\GPWebPay\Signer\SignerProvider;


    // setup services
    $configFactory = new ConfigFactory(new PaymentConfigFactory());
    $config = $configFactory->create(
        [
            ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
            ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
            ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
            ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            ConfigFactory::MERCHANT_NUMBER => '123456789',
            ConfigFactory::DEPOSIT_FLAG => 1,
            ConfigFactory::RESPONSE_URL => 'http://example.com/proccess-gpw-response',
        ],
    );

    $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
    $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
    $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());

    // create Request

    $operation = new Operation(
        orderNumber: new OrderNumber(time()),
        amount: new AmountInPennies(10000),
        currency: new Currency(CurrencyEnum::CZK()),
    );

    $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY())); // allowed payment types
    $operation->addParam(new MerOrderNum('12345678')); // Reference number

    $request = $requestFactory->create($operation);


    // render html button for clinet
    echo sprintf('<a href="%s">This is pay link</a>', $request->getRequestUrl());



.. _example.response:
Handle Response from GPWP
#############################

Simple example how handle response

.. code-block:: php

    <?php

    declare(strict_types=1);

    use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
    use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
    use Pixidos\GPWebPay\Factory\ResponseFactory;
    use Pixidos\GPWebPay\ResponseProvider;
    use Pixidos\GPWebPay\Signer\SignerFactory;
    use Pixidos\GPWebPay\Signer\SignerProvider;

    // setup services
    $configFactory = new ConfigFactory(new PaymentConfigFactory());
    $config = $configFactory->create(
        [
            ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
            ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
            ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
            ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            ConfigFactory::MERCHANT_NUMBER => '123456789',
            ConfigFactory::DEPOSIT_FLAG => 1,
            ConfigFactory::RESPONSE_URL => 'http://example.com/proccess-gpw-response',
        ],
    );

    $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
    $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
    $responseProvider = new ResponseProvider(
        $config->getPaymentConfigProvider(),
        $signerProvider
    );


    // setup callbacks

    $responseProvider->addOnSuccess(function (\Pixidos\GPWebPay\Data\Response $response) {
        // do anything you need after payment is success
        echo 'Success';
    });
    // you can add more callbacks
    $responseProvider->addOnSuccess(function (\Pixidos\GPWebPay\Data\Response $response) {
        // do anything you need
        echo 'Success';
    });

    $responseProvider->addOnError(function (\Pixidos\GPWebPay\Exceptions\GPWebPayResultException $exception, \Pixidos\GPWebPay\Data\Response $response) {
        // do anything you need
        echo 'Success';
    });


    // process response from GPWebPay
    $response = $responseFactory->create($_GET);
    $responseProvider->provide($response);


.. note::

    For more examples you can study **tests/WorkflowTest.php**