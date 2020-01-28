<?php declare(strict_types=1);

use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

require_once __DIR__ . '/config_example.php';

$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
$responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
$provider = new ResponseProvider(
    $config->getPaymentConfigProvider(), $signerProvider
);

// Create response
$params = $_GET; // or $_POST depends on how you send request
$response = $responseFactory->create($params);

// now you have two variants of processing response
// you can add callbacks for onSuccess and onError events
// success callbacks
$provider->addOnSuccess(
    static function (IResponse $response) {
        // here is you code for processing response
    }
);

// error callback
$provider->addOnError(
    static function (GPWebPayException $exception, IResponse $response) {
        // here is you code for processing error
    }
);

// and next step is call

$provider->provide($response);


// OR you can processing as you want :)

// verify response signatures
if (!$provider->verifyPaymentResponse($response)) {
    // invalid verification
}
// success verification
if ($response->hasError()) {
    // here is you code for processing response error
}
// here is you code for processing response
