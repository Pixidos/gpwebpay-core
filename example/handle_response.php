<?php

declare(strict_types=1);

use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Exceptions\InvalidResponseException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

require_once __DIR__ . '/../vendor/autoload.php';

// setup services
$config = (new ConfigFactory(new PaymentConfigFactory()))->create(require __DIR__ . '/config.php');
$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
$responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
$responseProvider = new ResponseProvider($config->getPaymentConfigProvider(), $signerProvider);

// GP webpay returns the result by a redirect (GET) or by an automatically submitted form (POST),
// depending on the services used. Read the data of the actual request method, never both merged.
$params = 'POST' === ($_SERVER['REQUEST_METHOD'] ?? 'GET') ? $_POST : $_GET;

try {
    $response = $responseFactory->create($params);
} catch (InvalidResponseException $exception) {
    // Not a GP webpay response for this shop: missing or malformed fields, or an unknown gateway in MD.
    http_response_code(400);
    echo 'Invalid payment response.';

    return;
}

// Called only when both signatures are valid and PRCODE and SRCODE are 0.
$responseProvider->addOnSuccess(static function (ResponseInterface $response): void {
    // 1. Find your payment attempt by $response->getOrderNumber() (the ORDERNUMBER you sent) and check
    //    that it belongs to $response->getGatewayKey(). Unknown attempt: reject, change nothing.
    // 2. The response does not repeat the amount. It is bound to ORDERNUMBER by the signed request,
    //    so take the amount from your payment attempt, not from any received parameter.
    // 3. Record the successful attempt atomically, for example
    //    UPDATE payment_attempt SET status = 'paid' WHERE id = ? AND status = 'pending'.
    // 4. In the same transaction, guard fulfillment separately by an atomic unpaid-to-paid order transition
    //    or a unique fulfillment record keyed by order ID. Two attempts for the same order can both succeed;
    //    record additional successful attempts without fulfilling again. Replayed responses must also be harmless.
    echo 'Payment accepted.';
});

// Called for every failure. The callback must accept GPWebPayException, the common parent type.
$responseProvider->addOnError(static function (GPWebPayException $exception, ResponseInterface $response): void {
    if ($exception instanceof GPWebPayResultException) {
        // Authentic response, but the payment was declined, cancelled or failed.
        // Mark the payment attempt as failed (only if it is still pending) and let the customer try again.
        echo 'Payment failed: ' . htmlspecialchars($exception->translate('en'), ENT_QUOTES, 'UTF-8');

        return;
    }

    // SignerException: the signatures do not match, the data did not come from GP webpay
    // or were changed. Change nothing, log the attempt and reject the request.
    http_response_code(400);
    echo 'Invalid payment response.';
});

// Verifies DIGEST and DIGEST1 first, then calls exactly one kind of callback.
$responseProvider->provide($response);
