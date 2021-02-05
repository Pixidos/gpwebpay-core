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

namespace Pixidos\GPWebPay\Tests\Config;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\PaymentConfig;
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\LogicException;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;

class PaymentConfigProviderTest extends TestCase
{

    public function test(): void
    {
        $merchantNumber1 = new MerchantNumber('1234567890');
        $depositFlag1 = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES());

        $setting1 = new PaymentConfig(
            'http://localhost',
            $merchantNumber1,
            $depositFlag1,
            'czk'
        );

        $merchantNumber2 = new MerchantNumber('1234567891');
        $depositFlag2 = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::NO());

        $setting2 = new PaymentConfig(
            'http://local',
            $merchantNumber2,
            $depositFlag2,
            'eur'
        );

        $configProvider = new PaymentConfigProvider();
        $configProvider->setDefaultGateway('czk');
        $configProvider->addPaymentConfig($setting1);
        $configProvider->addPaymentConfig($setting2);


        self::assertSame('http://localhost', $configProvider->getUrl($configProvider->getDefaultGateway()));
        self::assertSame($merchantNumber1, $configProvider->getMerchantNumber($configProvider->getDefaultGateway()));
        self::assertSame($depositFlag1, $configProvider->getDepositFlag($configProvider->getDefaultGateway()));


        self::assertSame('http://localhost', $configProvider->getUrl('czk'));
        self::assertSame($merchantNumber1, $configProvider->getMerchantNumber('czk'));
        self::assertSame($depositFlag1, $configProvider->getDepositFlag('czk'));


        self::assertSame('http://local', $configProvider->getUrl('eur'));
        self::assertSame($merchantNumber2, $configProvider->getMerchantNumber('eur'));
        self::assertSame($depositFlag2, $configProvider->getDepositFlag('eur'));
    }


    public function testGetDefaultGatewayForgotSettingThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(
            'You need first set default key by Pixidos\GPWebPay\Config\PaymentConfigProvider::setDefaultGateway'
        );

        $configProvider = new PaymentConfigProvider();
        $configProvider->getDefaultGateway();
    }


    public function testGetNonExistGatewayThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Config for key: "eur" not exist. Possible keys are: "czk"'
        );

        $configProvider = new PaymentConfigProvider();
        $configProvider->setDefaultGateway('czk');
        $merchantNumber = new MerchantNumber('1234567890');
        $depositFlag = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES());

        $configProvider->addPaymentConfig(
            new PaymentConfig(
                'http://localhost',
                $merchantNumber,
                $depositFlag,
                'czk'
            )
        );

        $configProvider->getUrl('eur');
    }
}
