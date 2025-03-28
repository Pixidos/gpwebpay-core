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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

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
}
