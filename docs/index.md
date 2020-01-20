# GPWebPay core library

## About

Is it library from integrate [GPWebPay](https://www.gpwebpay.cz/) pay gateway service to your project.
It framework agnostic and you can use with any php framework or pure php.

## Installing

best way to installing is using [Composer](http://getcomposer.org/)

```bash
$ composer require pixidos/gpwebpay-core
``` 

## Usage

####Create services

```php
<?php declare(strict_types=1);

use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;
use Pixidos\GPWebPay\Provider;
use Pixidos\GPWebPay\Settings\SettingsFactory;
use Pixidos\GPWebPay\Signer\SignerFactory;

$settings = SettingsFactory::create(
    __DIR__ . '/_certs/test.pem', // path to private certificate
    '1234567', // password for private certificate
    __DIR__ . '/_certs/test-pub.pem', // public certificate by GPWebPay
    'https://test.3dsecure.gpwebpay.com/unicredit/order.do', // url address to pay gatewey by GPWebPay and selected bank
    '123456789', // Merchant number
    1 // Deposit flag -> possible values 1 or 0
);


$signerFactory = new SignerFactory($settings);

$provider = new Provider(
    $settings,
    $signerFactory,
    new RequestFactory($settings, $signerFactory),
    new ResponseFactory($settings)
);

```

#### Operation and Request

```php
$operation = new Operation(
    new OrderNumber('123456'),
    new Amount(1000.00),
    new Currency(CurrencyEnum::CZK()),
    null,
    new ResponseUrl('http://example.com/proccess-gpw-response')
);

$request = $provider->createRequest($operation);
```

#### HTML
you can simple create HMTL pay button of rendering form

```php
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
```

####Process Response

```php
<?php
$params = $_GET; //or $_POST depends on method of you send pay request

 $factory = new \Pixidos\GPWebPay\Factory\ResponseFactory($settings);
 $response = $factory->create($params);

if($response->hasError()){
 //Process Error 
 // for example:
 $error = new \Pixidos\GPWebPay\Data\ResponseError($response->getPrcode(), $response->getSrcode());
 echo $error->getMessage('cz' /** en */);

}

// process success response.

```
