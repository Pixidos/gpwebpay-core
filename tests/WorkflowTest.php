<?php

declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\Config;
use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
use Pixidos\GPWebPay\Data\Operation as OperationData;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Param\AmountInPennies;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\MerOrderNum;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\PayMethods;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

class WorkflowTest extends TestCase
{
    private const ORDER_NUMBER = '123456';
    private const MER_ORDER_NUM = '12345678';

    /**
     * @return Generator<int, array<string>>
     */
    public static function usedGateway(): Generator
    {
        yield ['czk'];
        yield ['eur'];
    }

    public function testSingleConfigWorkflow(): void
    {
        $config = $this->createSingleConfig();
        $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
        $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
        $responseProvider = new ResponseProvider(
            $config->getPaymentConfigProvider(),
            $signerProvider
        );

        $operation = new OperationData(
            orderNumber: new OrderNumber(self::ORDER_NUMBER),
            amount: new AmountInPennies(10000),
            currency: new Currency(CurrencyEnum::CZK()),
        );

        $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));
        $operation->addParam(new MerOrderNum(self::MER_ORDER_NUM));

        $request = $requestFactory->create($operation);

        $digest1 = 'fvc6MMV3j4w41mLYmNdBw3X3h9R3GfOTO6ZJBRZnsWyW4nQFmGqwrnvdXSfBUzQkVkbWtuxiO8Y3xJ9lMsxzLk9%2BsXYSi8buITvMgbS9Waj6t1Y5VMOyzCkJFp9vIzxPKHsSNuswlNazcxykTiRx85D7ja37zA67QwtsxAHO7F5lf2ZSeblbdVbPW8bLBWLyBUyo%2FX0xfLKwD%2FTci%2Bs0Z9qrWAnOjJNgc4OYNKFcd6afAVXsSyU22SetwliibQwmxPbmG5Ze7ER%2BlrsSEFxLaV0wO8QxR9dlr21jh%2FLrp8PWDDtShKNQRPZM8rEIutpcCQYImoyJzfqKvD9GWnbTRQ%3D%3D';
        $expectedUrl = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=10000&CURRENCY=203&DEPOSITFLAG=1&MERORDERNUM=12345678&URL=http%3A%2F%2Fexample.com%2Fproccess-gpw-response&PAYMETHODS=CRD%2CGPAY&DIGEST=' . $digest1;

        self::assertSame($expectedUrl, $request->getRequestUrl());

        $getParams =
            [
                Param::OPERATION => 'CREATE_ORDER',
                Param::ORDERNUMBER => self::ORDER_NUMBER,
                Param::MERORDERNUM => self::MER_ORDER_NUM,
                ResponseInterface::PRCODE => '0',
                ResponseInterface::SRCODE => '0',
                ResponseInterface::RESULTTEXT => 'resulttext',
                Param::TOKEN => 'XXXX',
            ];

        $getParams = $this->generateSignedParams($signerProvider, $getParams);

        $response = $responseFactory->create($getParams);

        self::assertSame(self::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(self::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame('default', $response->getGatewayKey());

        $responseProvider->provide($response);
    }

    public function testSingleConfigWorkflowErrorParams(): void
    {
        $config = $this->createSingleConfig();
        $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
        $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
        $responseProvider = new ResponseProvider(
            $config->getPaymentConfigProvider(),
            $signerProvider
        );

        $operation = new OperationData(
            orderNumber: new OrderNumber(self::ORDER_NUMBER),
            amount: new AmountInPennies(10000),
            currency: new Currency(CurrencyEnum::CZK()),
        );
        $operation->addParam(new Md('foo'));

        $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));
        $operation->addParam(new MerOrderNum(self::MER_ORDER_NUM));

        $request = $requestFactory->create($operation);

        $digest1 = 'ZevT0tL7LrjHF06pw%2BP6o11y2MhaQhMCjn0TSYOZ9F1Cxsj5SMG5Gm%2FAv5SMZcf4tcny5PtT3BakP6WDdPPkzLCYFn6tYenY1THMCD6%2B4V2eoYYbfTyfO0f%2FfZezPbgROdXg%2BFddj3b8LbuMGUIH6MXtOG2JzsKk%2F1Qv%2FwfLVYtN36UMlDPrHDm2%2FxXUWUoKgpLd4y7R5p71FZ1RNPGii95PbhE6DwW460bn4pDOkhCh3WxUSGHZHQy%2FR2osgMkDE0pJrqNITwxaDoAdMgzDkNVmf4oS2TDpEadHuGHL0933ZiY6MWBW59eVFYx96Ozq%2F2FAYtZRwPwZbDSo4klPBQ%3D%3D';
        $expectedUrl = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=10000&CURRENCY=203&DEPOSITFLAG=1&MERORDERNUM=12345678&URL=http%3A%2F%2Fexample.com%2Fproccess-gpw-response&MD=%7Cfoo&PAYMETHODS=CRD%2CGPAY&DIGEST=' . $digest1;

        self::assertSame($expectedUrl, $request->getRequestUrl());

        $getParams =
            [
                Param::OPERATION => 'CREATE_ORDER',
                Param::ORDERNUMBER => self::ORDER_NUMBER,
                Param::MERORDERNUM => self::MER_ORDER_NUM,
                Param::MD => '|foo',
                ResponseInterface::PRCODE => '14',
                ResponseInterface::SRCODE => '0',
                ResponseInterface::RESULTTEXT => 'Duplicate order number',
            ];

        // only workaroud to test response verification, this signature is normally done by GPWP and digest and digest1 are already in response
        $getParams = $this->generateSignedParams($signerProvider, $getParams);

        $response = $responseFactory->create($getParams);

        self::assertSame(self::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(self::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame('default', $response->getGatewayKey());
        self::assertSame(14, $response->getPrcode());
        self::assertSame(0, $response->getSrcode());

        try {
            $responseProvider->provide($response);

            self::fail('Exception ' . GPWebPayResultException::class . ' should be thrown');
        } catch (GPWebPayResultException $exception) {
            self::assertSame('Response has an error.', $exception->getMessage());
            self::assertSame('Duplicate order number', $exception->getResultText());
            self::assertSame(14, $exception->getPrcode());
            self::assertSame(0, $exception->getSrcode());
        };
    }

    public function testMultiConfigWorkflowUseDefaultGateway(): void
    {
        $config = $this->createMultiConfig();
        $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
        $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
        $responseProvider = new ResponseProvider(
            $config->getPaymentConfigProvider(),
            $signerProvider
        );

        // Use default gateway
        $operation = new OperationData(
            orderNumber: new OrderNumber(self::ORDER_NUMBER),
            amount: new AmountInPennies(10000),
            currency: new Currency(CurrencyEnum::CZK()),
        );

        $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));
        $operation->addParam(new MerOrderNum(self::MER_ORDER_NUM));

        $request = $requestFactory->create($operation);

        $digest = 'fvc6MMV3j4w41mLYmNdBw3X3h9R3GfOTO6ZJBRZnsWyW4nQFmGqwrnvdXSfBUzQkVkbWtuxiO8Y3xJ9lMsxzLk9%2BsXYSi8buITvMgbS9Waj6t1Y5VMOyzCkJFp9vIzxPKHsSNuswlNazcxykTiRx85D7ja37zA67QwtsxAHO7F5lf2ZSeblbdVbPW8bLBWLyBUyo%2FX0xfLKwD%2FTci%2Bs0Z9qrWAnOjJNgc4OYNKFcd6afAVXsSyU22SetwliibQwmxPbmG5Ze7ER%2BlrsSEFxLaV0wO8QxR9dlr21jh%2FLrp8PWDDtShKNQRPZM8rEIutpcCQYImoyJzfqKvD9GWnbTRQ%3D%3D';
        $expectedUrl = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=10000&CURRENCY=203&DEPOSITFLAG=1&MERORDERNUM=12345678&URL=http%3A%2F%2Fexample.com%2Fproccess-gpw-response&PAYMETHODS=CRD%2CGPAY&DIGEST=' . $digest;

        self::assertSame($expectedUrl, $request->getRequestUrl());

        $params_GET =
            [
                Param::OPERATION => 'CREATE_ORDER',
                Param::ORDERNUMBER => self::ORDER_NUMBER,
                Param::MERORDERNUM => self::MER_ORDER_NUM,
                ResponseInterface::PRCODE => '0',
                ResponseInterface::SRCODE => '0',
                ResponseInterface::RESULTTEXT => 'resulttext',
                Param::TOKEN => 'XXXX',
            ];

        // only workaroud to test response verification, this signature is normally done by GPWP and digest and digest1 are already in response
        $params_GET = $this->generateSignedParams($signerProvider, $params_GET);

        $response = $responseFactory->create($params_GET);

        self::assertSame(self::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(self::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame('czk', $response->getGatewayKey());

        $responseProvider->provide($response);
    }

    /**
     * @dataProvider usedGateway
     * @param string $usedGateway
     * @return void
     */
    public function testMultiConfigWorkflowUseConcreteGateway(string $usedGateway): void
    {
        $config = $this->createMultiConfig();
        $signerProvider = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
        $responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
        $responseProvider = new ResponseProvider(
            $config->getPaymentConfigProvider(),
            $signerProvider
        );


        // Use default gateway
        $operation = new OperationData(
            orderNumber: new OrderNumber(self::ORDER_NUMBER),
            amount: new AmountInPennies(10000),
            currency: new Currency(CurrencyEnum::CZK()),
            gateway: $usedGateway
        );

        $operation->addParam(new PayMethods(PayMethod::CARD(), PayMethod::GOOGLE_PAY()));
        $operation->addParam(new MerOrderNum(self::MER_ORDER_NUM));
        $request = $requestFactory->create($operation);
        $singer = $signerProvider->get($usedGateway);

        $merchantNumber = 'czk' === $usedGateway ? '123456789' : '123456780';
        $depositFlag = 'czk' === $usedGateway ? 1 : 0;

        $digest = rawurlencode($singer->sign($request->getDigestParams()));
        $expectedUrl = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=' . $merchantNumber . '&OPERATION=CREATE_ORDER&ORDERNUMBER=123456'
            . '&AMOUNT=10000&CURRENCY=203&DEPOSITFLAG=' . $depositFlag . '&MERORDERNUM=12345678&URL=http%3A%2F%2Fexample.com%2Fproccess-gpw-response&' .
            'MD=' . $usedGateway . '&PAYMETHODS=CRD%2CGPAY&DIGEST=' . $digest;

        self::assertSame($expectedUrl, $request->getRequestUrl());

        $params_GET =
            [
                Param::OPERATION => 'CREATE_ORDER',
                Param::ORDERNUMBER => self::ORDER_NUMBER,
                Param::MERORDERNUM => self::MER_ORDER_NUM,
                Param::MD => $usedGateway,
                ResponseInterface::PRCODE => '0',
                ResponseInterface::SRCODE => '0',
                ResponseInterface::RESULTTEXT => 'resulttext',
                Param::TOKEN => 'XXXX',
            ];

        // only workaroud to test response verification, this signature is normally done by GPWP and digest and digest1 are already in response
        $params_GET = $this->generateSignedParams($signerProvider, $params_GET, $merchantNumber, $usedGateway);

        $response = $responseFactory->create($params_GET);

        self::assertSame(self::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(self::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame($usedGateway, $response->getGatewayKey());

        $responseProvider->provide($response);
    }

    private function createSingleConfig(): Config
    {
        $configFactory = new ConfigFactory(new PaymentConfigFactory());

        return $configFactory->create(
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
    }


    private function createMultiConfig(): Config
    {
        $configFactory = new ConfigFactory(new PaymentConfigFactory());

        return $configFactory->create(
            [
            'czk' => [
                ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
                ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                ConfigFactory::MERCHANT_NUMBER => '123456789',
                ConfigFactory::DEPOSIT_FLAG => 1,
                ConfigFactory::RESPONSE_URL => 'http://example.com/proccess-gpw-response',
            ],
            'eur' => [
                ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test2.pem',
                ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub2.pem',
                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                ConfigFactory::MERCHANT_NUMBER => '123456780',
                ConfigFactory::DEPOSIT_FLAG => 0,
                ConfigFactory::RESPONSE_URL => 'http://example.com/proccess-gpw-response',
            ],
            ],
            'czk'
        );
    }

    /**
     * @param SignerProvider        $signerProvider
     * @param array<string, string> $params
     * @return array<string, string>
     */
    private function generateSignedParams(
        SignerProvider $signerProvider,
        array $params,
        string $merchantNumber = '123456789',
        string|null $gateway = null
    ): array {
        // only workaroud to test response verification, this signature is normally done by GPWP and digest and digest1 are already in response
        $singer = $signerProvider->get($gateway);
        $digest = $singer->sign($params);
        $digest1Params = $params;
        $digest1Params[Param::MERCHANTNUMBER] = $merchantNumber;
        $digest1 = $singer->sign($digest1Params);
        $params[Param::DIGEST] = $digest;
        $params[ResponseInterface::DIGEST1] = $digest1;

        return $params;
    }
}
