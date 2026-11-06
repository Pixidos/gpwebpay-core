<?php

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Config\Factory;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Param\AmountInPennies;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;
use Pixidos\GPWebPay\Tests\TestHelpers;

class ConfigFactoryTest extends TestCase
{
    private const CZK = 'czk';
    private const EUR = 'eur';


    public function testSuccessCreateSettingsFromSingleParams(): void
    {
        $configFactory = new ConfigFactory(new PaymentConfigFactory());
        $config = $configFactory->create(
            [
                ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
                ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                ConfigFactory::MERCHANT_NUMBER => '123456789',
                ConfigFactory::DEPOSIT_FLAG => 1,
            ],
        );


        $configProvider = $config->getPaymentConfigProvider();

        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl());
        self::assertSame('123456789', (string)$configProvider->getMerchantNumber());
        self::assertSame('1', (string)$configProvider->getDepositFlag());

        $signerConfig = $config->getSignerConfigProvider()->getConfig();

        self::assertSame(__DIR__ . '/_certs/test.pem', $signerConfig->getPrivateKey());
        self::assertSame('1234567', $signerConfig->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub.pem', $signerConfig->getPublicKey());
    }

    public function testSuccessCreateSettingsFromArray(): void
    {
        $configFactory = new ConfigFactory(new PaymentConfigFactory());
        $config = $configFactory->create(
            [
                'czk' => [
                    ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
                    ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                    ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                    ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                    ConfigFactory::MERCHANT_NUMBER => '123456789',
                    ConfigFactory::DEPOSIT_FLAG => 1,
                ],
                'eur' => [
                    ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test2.pem',
                    ConfigFactory::PRIVATE_KEY_PASSPHRASE => '12345678',
                    ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub2.pem',
                    ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                    ConfigFactory::MERCHANT_NUMBER => '123456780',
                    ConfigFactory::DEPOSIT_FLAG => 0,
                ],
            ],
            self::CZK
        );

        $configProvider = $config->getPaymentConfigProvider();

        // test defaults
        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl());
        self::assertSame('123456789', (string)$configProvider->getMerchantNumber());
        self::assertSame('1', (string)$configProvider->getDepositFlag());

        // test czk
        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl(self::CZK));
        self::assertSame('123456789', (string)$configProvider->getMerchantNumber(self::CZK));
        self::assertSame('1', (string)$configProvider->getDepositFlag(self::CZK));

        //test eur
        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl(self::EUR));
        self::assertSame('123456780', (string)$configProvider->getMerchantNumber(self::EUR));
        self::assertSame('0', (string)$configProvider->getDepositFlag(self::EUR));

        //test default
        $signerConfigDefault = $config->getSignerConfigProvider()->getConfig();
        self::assertSame(__DIR__ . '/_certs/test.pem', $signerConfigDefault->getPrivateKey());
        self::assertSame('1234567', $signerConfigDefault->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub.pem', $signerConfigDefault->getPublicKey());

        //test czk
        $signerConfigCzk = $config->getSignerConfigProvider()->getConfig(self::CZK);
        self::assertSame(__DIR__ . '/_certs/test.pem', $signerConfigCzk->getPrivateKey());
        self::assertSame('1234567', $signerConfigCzk->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub.pem', $signerConfigCzk->getPublicKey());

        // test eur
        $signerConfigEur = $config->getSignerConfigProvider()->getConfig(self::EUR);
        self::assertSame(__DIR__ . '/_certs/test2.pem', $signerConfigEur->getPrivateKey());
        self::assertSame('12345678', $signerConfigEur->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub2.pem', $signerConfigEur->getPublicKey());
    }

    public function testDataCreateSettingsFromArrayWithWrongDefaultGateway(): void
    {

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The key for defautlGateway: "wrong_default_gateway" is not included in the configuration parameters. Keys in the parameters "czk, eur"');

        $configFactory = new ConfigFactory(new PaymentConfigFactory());
        $configFactory->create(
            [
                        'czk' => [
                                ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
                                ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                                ConfigFactory::MERCHANT_NUMBER => '123456789',
                                ConfigFactory::DEPOSIT_FLAG => 1,
                        ],
                        'eur' => [
                                ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test2.pem',
                                ConfigFactory::PRIVATE_KEY_PASSPHRASE => '12345678',
                                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub2.pem',
                                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                                ConfigFactory::MERCHANT_NUMBER => '123456780',
                                ConfigFactory::DEPOSIT_FLAG => 0,
                        ],
                ],
            'wrong_default_gateway'
        );
    }

    public function testGatewayKeysAreCaseInsensitive(): void
    {
        $config = TestHelpers::createMultiGatewayConfig(
            'https://shop.example/czk-return',
            'https://shop.example/eur-return',
            'CZK',
            'CZK',
            'Eur'
        );
        $payment = $config->getPaymentConfigProvider();
        $signer = $config->getSignerConfigProvider();

        self::assertSame('czk', $payment->getDefaultGateway());
        self::assertSame('czk', $signer->getDefaultGateway());
        self::assertSame('eur', $payment->getGateway('EUR'));
        self::assertSame(TestHelpers::CZK_MERCHANT_NUMBER, (string)$payment->getMerchantNumber());
        self::assertSame(TestHelpers::EUR_MERCHANT_NUMBER, (string)$payment->getMerchantNumber('eUR'));
        self::assertSame($signer->getConfig('eur'), $signer->getConfig('EUR'));
        self::assertSame('https://shop.example/eur-return', (string)$payment->getResponseUrl('Eur'));
    }

    public function testSingleGatewayWithMixedCaseDefaultName(): void
    {
        $config = (new ConfigFactory(new PaymentConfigFactory()))->create($this->gateway('test'), 'Main');

        self::assertSame('main', $config->getPaymentConfigProvider()->getDefaultGateway());
        self::assertSame('123456789', (string)$config->getPaymentConfigProvider()->getMerchantNumber());
        self::assertSame('123456789', (string)$config->getPaymentConfigProvider()->getMerchantNumber('MAIN'));
    }

    public function testCamelCaseGatewayKeyWorksForWholePayment(): void
    {
        $config = TestHelpers::createMultiGatewayConfig('https://shop.example/r', null, 'czkShop', 'czkShop');
        $signers = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $requestFactory = new RequestFactory($config->getPaymentConfigProvider(), $signers);

        $request = $requestFactory->create(
            new Operation(new OrderNumber('1'), new AmountInPennies(100), new Currency(CurrencyEnum::CZK()), 'czkShop')
        );
        $md = $request->getParams()[Param::MD];
        $received = TestHelpers::signAsGateway(
            $signers->get('czkShop'),
            ['OPERATION' => 'CREATE_ORDER', 'ORDERNUMBER' => '1', 'MD' => $md, 'PRCODE' => '0', 'SRCODE' => '0'],
            TestHelpers::CZK_MERCHANT_NUMBER
        );
        $response = (new ResponseFactory($config->getPaymentConfigProvider()))->create($received);

        self::assertSame('czkshop', $md);
        self::assertSame('czkshop', $response->getGatewayKey());
        self::assertTrue(
            (new ResponseProvider($config->getPaymentConfigProvider(), $signers))->verifyPaymentResponse($response)
        );
    }

    public function testGatewayKeysDifferingOnlyInCaseAreRejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Gateway keys are case-insensitive, "czk" is configured more than once.');

        (new ConfigFactory(new PaymentConfigFactory()))->create(
            ['czk' => $this->gateway('test'), 'CZK' => $this->gateway('test2')],
            'czk'
        );
    }

    /**
     * @dataProvider invalidGatewayKeys
     */
    public function testGatewayKeyThatCannotBeSentInMdIsRejected(string $gateway): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('must be printable ASCII without "|" and "#"');

        (new ConfigFactory(new PaymentConfigFactory()))->create([$gateway => $this->gateway('test')], $gateway);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function invalidGatewayKeys(): array
    {
        return [
            'separator' => ['czk|eur'],
            'suffix marker' => ['czk#1'],
            'non-ASCII' => ['koruna-č'],
            'empty' => [''],
        ];
    }

    /**
     * @return array{privateKey: string, url: string, merchantNumber: string, depositFlag: int,
     *     privateKeyPassphrase: string, publicKey: string}
     */
    private function gateway(string $key): array
    {
        return [
            ConfigFactory::PRIVATE_KEY => __DIR__ . '/../../_certs/' . $key . '.pem',
            ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
            ConfigFactory::PUBLIC_KEY => __DIR__ . '/../../_certs/' . str_replace('test', 'test-pub', $key) . '.pem',
            ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/pgw/order.do',
            ConfigFactory::MERCHANT_NUMBER => '123456789',
            ConfigFactory::DEPOSIT_FLAG => 1,
        ];
    }
}
