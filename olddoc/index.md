# GPWebPay core library

Is it library from integrate [GPWebPay](https://www.gpwebpay.cz/) pay gateway service to your project.
It framework agnostic and you can use with any php framework or pure php.

[Documentation for version 1.x ](v1/index.md)

## Framework integration

- [Nette](https://github.com/Pixidos/GPWebPay)
- Symfony -> lib in development

## Installing

best way to installing is using [Composer](http://getcomposer.org/)

```bash
$ composer require pixidos/gpwebpay-core
``` 

## Usage

### Create Config

```php
use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;

$configFactory = new ConfigFactory(new PaymentConfigFactory());

$config = $configFactory->create(
    [
        ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
        ConfigFactory::PRIVATE_KEY_PASSWORD => '1234567',
        ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
        ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
        ConfigFactory::MERCHANT_NUMBER => '123456789',
        ConfigFactory::DEPOSIT_FLAG => 1,
        ConfigFactory::RESPONSE_URL => 'http://example.com/proccess-gpw-response'
        // optional, you can set here or in operation
    ]
);

```

Or you can setup more gateways
```php
$config = $configFactory->create(
    [
        'czk' => [
            ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
            ConfigFactory::PRIVATE_KEY_PASSWORD => '1234567',
            ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
            ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            ConfigFactory::MERCHANT_NUMBER => '123456789',
            ConfigFactory::DEPOSIT_FLAG => 1,
        ],
        'eur' => [
            ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test2.pem',
            ConfigFactory::PRIVATE_KEY_PASSWORD => '12345678',
            ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub2.pem',
            ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            ConfigFactory::MERCHANT_NUMBER => '123456780',
            ConfigFactory::DEPOSIT_FLAG => 1,
        ],
    ],
    'czk' // what gateway is default
);
```

### Operation and Request

```php
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

$signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
$requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);


$operation = new Operation(
    new OrderNumber('123456'),
    new Amount(1000.00),
    new Currency(CurrencyEnum::CZK())
//, 'czk' -> set up gateway
//, new ResponseUrl('http://example.com/proccess-gpw-response') -> set up response url
);
```
more params you can simple add by addParam
for example add PayMethods param
```php
$operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));
```
And now you simple create Request
```php
$request = $requestFactory->create($operation);
```


### HTML
you can simple create HMTL pay button

```php
echo sprintf('<a href="%s">This is pay link</a>', $request->getRequestUrl());
```

or you can rendering form
```php
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

### Process Response

```php
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

// include config
require_once __DIR__ . '/config.php';

// Create services
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
```
