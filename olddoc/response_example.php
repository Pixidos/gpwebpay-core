<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

use Pixidos\GPWebPay\Data\ResponseInterface;
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
$provider
    ->addOnSuccess(
        static function (ResponseInterface $response) {
            // here is you code for processing response
        }
    )
    ->addOnSuccess(
        static function (ResponseInterface $response) {
            // here is you code for processing response
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
