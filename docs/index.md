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

#### Setting

```php
$settings = \Pixidos\GPWebPay\Settings\SettingsFactory::create(
            __DIR__ . '/_certs/test.pem', // path to private certificate
            '1234567', // password for private certificate
            __DIR__ . '/_certs/test-pub.pem', // public certificate by GPWebPay
            'https://test.3dsecure.gpwebpay.com/unicredit/order.do', // url address to pay gatewey by GPWebPay and selected bank
            '123456789', // Merchant number
            1, // Deposit flag -> possible values 1 or 0
        );
```

you can configure more then one gateway. 

For example: When you need receive payment from CZK a EUR [advanced example](advanced-settings.md)

#### Signer factory
```php
$signerFactory = new \Pixidos\GPWebPay\Signer\SignerFactory($settings);
```

#### Provider

```php
$provider = new Provider(
            $settings,
            $signerFactory,
            new RequestFactory($settings, $signerFactory),
            new ResponseFactory($settings)
        );
```

#### Operation

```php
$operation = new Operation(
            new OrderNumber('123456'),
            new Amount(1000.00),
            new Currency(CurrencyEnum::CZK()),
            null,
            new ResponseUrl('http://example.com/proccess-gpw-response')
        );
```

#### HTML PayButton
you can simple create HMTL pay button
```php
$request = $provider->createRequest($operation);
echo "<a href='$request->getRequestUrl()'>This is pay link</a>';
```
#### Form
or you can use form for post action
```php
<form action="<?= $request->getRequestUrl(true)?>">
<?php
foreach ($request->getParams() as $param){
    echo "<input type=hidden value='$param->getValue()' name='$param->getParamName()'>";
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
