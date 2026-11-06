<?php

declare(strict_types=1);

use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\PayMethod;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Param\AddInfo;
use Pixidos\GPWebPay\Param\AmountInPennies;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\MerOrderNum;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\PayMethods;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

require_once __DIR__ . '/../vendor/autoload.php';

// setup services
$config = (new ConfigFactory(new PaymentConfigFactory()))->create(require __DIR__ . '/config.php');
$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
$requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);

// The order being paid, loaded from your database.
$order = ['number' => 2026000123, 'totalInHellers' => 129900, 'customerName' => 'Jan Novak', 'email' => 'jan@example.com'];

// Save a new payment attempt for the order (status "pending", amount, currency, gateway) before
// redirecting the customer. Its unique numeric ID, e.g. an AUTO_INCREMENT key, is the ORDERNUMBER:
// at most 15 digits and never reused for another request. Do not derive it from time(),
// two customers can start a payment in the same second.
$paymentAttemptId = 7001;

$operation = new Operation(
    orderNumber: new OrderNumber($paymentAttemptId),
    amount: new AmountInPennies($order['totalInHellers']), // integer amount in hellers: 1 299.00 CZK
    currency: new Currency(CurrencyEnum::CZK()),
);
$operation->addParam(new MerOrderNum($order['number'])); // your order number, shown on statements
$operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY(), PayMethod::APPLE_PAY()));

// Cardholder data required by the card schemes (PSD2): the name and an e-mail or a phone number.
// Send only real data you have and omit elements you do not have; never invent values.
// The name allows ASCII characters only. Validate the structure against the ADDINFO XSD from the GP webpay portal.
$xml = static fn (string $value): string => htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
$operation->addParam(new AddInfo(
    '<additionalInfoRequest xmlns="http://gpe.cz/gpwebpay/additionalInfo/request" version="5.0">'
    . '<cardholderInfo><cardholderDetails>'
    . '<name>' . $xml($order['customerName']) . '</name>'
    . '<email>' . $xml($order['email']) . '</email>'
    . '</cardholderDetails></cardholderInfo>'
    . '</additionalInfoRequest>' // no line breaks or spaces between elements
));

$request = $requestFactory->create($operation);

// Recommended: an automatically or manually submitted POST form. It has no URL length limit
// and keeps UTF-8 in ADDINFO intact. Every value must be HTML-escaped.
$escape = static fn (string $value): string => htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
echo '<form method="post" action="' . $escape($request->getRequestUrl(true)) . '">' . "\n";
foreach ($request->getParams() as $name => $value) {
    echo '<input type="hidden" name="' . $escape($name) . '" value="' . $escape($value) . '">' . "\n";
}
echo '<button type="submit">Pay</button>' . "\n";
echo "</form>\n";

// Alternatively a link (GET). Keep the request short: browsers and servers limit URL length.
// echo '<a href="' . $escape($request->getRequestUrl()) . '">Pay</a>';
