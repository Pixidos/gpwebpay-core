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

namespace Pixidos\GPWebPay\Tests\Config\Factory;

use PHPStan\Testing\TestCase;
use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;

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
                ConfigFactory::PRIVATE_KEY_PASSWORD => '1234567',
                ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                ConfigFactory::MERCHANT_NUMBER => '123456789',
                ConfigFactory::DEPOSIT_FLAG => 1,
            ],
            self::CZK
        );


        $configProvider = $config->getPaymentConfigProvider();

        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl(self::CZK));
        self::assertSame('123456789', (string)$configProvider->getMerchantNumber(self::CZK));
        self::assertSame('1', (string)$configProvider->getDepositFlag(self::CZK));

        $signerConfig = $config->getSignerConfigProvider()->getConfig(self::CZK);

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
                    ConfigFactory::DEPOSIT_FLAG => 0,
                ],
            ],
            self::CZK
        );

        $configProvider = $config->getPaymentConfigProvider();

        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl(self::CZK));
        self::assertSame('123456789', (string)$configProvider->getMerchantNumber(self::CZK));
        self::assertSame('1', (string)$configProvider->getDepositFlag(self::CZK));
        self::assertSame('https://test.3dsecure.gpwebpay.com/unicredit/order.do', $configProvider->getUrl(self::EUR));
        self::assertSame('123456780', (string)$configProvider->getMerchantNumber(self::EUR));
        self::assertSame('0', (string)$configProvider->getDepositFlag(self::EUR));

        $signerConfigCzk = $config->getSignerConfigProvider()->getConfig(self::CZK);

        self::assertSame(__DIR__ . '/_certs/test.pem', $signerConfigCzk->getPrivateKey());
        self::assertSame('1234567', $signerConfigCzk->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub.pem', $signerConfigCzk->getPublicKey());

        $signerConfigEur = $config->getSignerConfigProvider()->getConfig(self::EUR);

        self::assertSame(__DIR__ . '/_certs/test2.pem', $signerConfigEur->getPrivateKey());
        self::assertSame('12345678', $signerConfigEur->getPrivateKeyPassword());
        self::assertSame(__DIR__ . '/_certs/test-pub2.pem', $signerConfigEur->getPublicKey());
    }
}
