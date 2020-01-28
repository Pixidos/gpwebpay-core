<?php declare(strict_types=1);

use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;


require_once __DIR__ . '/config_example.php';

$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
$requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);


$operation = new Operation(
    new OrderNumber('123456'),
    new Amount(1000.00),
    new Currency(CurrencyEnum::CZK())
//, 'czk' -> set up gateway
//, new ResponseUrl('http://example.com/proccess-gpw-response') -> set up response url
);

//more params you can simple add by addParam
// for example add PayMethods param
//$operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));

$request = $requestFactory->create($operation);

// RENDERING

// Render button
echo sprintf('<a href="%s">This is pay link</a>', $request->getRequestUrl());

// OR CREATE FORM

?>
<form action="<?= $request->getRequestUrl(true) ?>">
    <?php
    /** @var IParam $param */
    foreach ($request->getParams() as $param) {
        echo sprintf('<input type=hidden value="%s" name="%s">%s', $param->getValue(), $param->getParamName(), "\n\r");
    }
    ?>
    <input type="submit" value="Pay">
</form>
