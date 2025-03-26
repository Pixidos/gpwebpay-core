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
