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

namespace Pixidos\GPWebPay\Tests\Settings;

use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Settings\Setting;
use Pixidos\GPWebPay\Settings\Settings;
use PHPUnit\Framework\TestCase;

class SettingsTest extends TestCase
{

    public function test(): void
    {
        $privateKey1 = __DIR__ . '/../_certs/test.pem';
        $publicKey1 = __DIR__ . '/../_certs/test-pub.pem';
        $merchantNumber1 = new MerchantNumber('1234567890');
        $depositFlag1 = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES());

        $setting1 = new Setting(
            $privateKey1,
            '123456789',
            $publicKey1,
            'http://localhost',
            $merchantNumber1,
            $depositFlag1,
            'czk'
        );

        $privateKey2 = __DIR__ . '/../_certs/test2.pem';
        $publicKey2 = __DIR__ . '/../_certs/test-pub2.pem';
        $merchantNumber2 = new MerchantNumber('1234567891');
        $depositFlag2 = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::NO());

        $setting2 = new Setting(
            $privateKey2,
            '1234567890',
            $publicKey2,
            'http://local',
            $merchantNumber2,
            $depositFlag2,
            'eur'
        );

        $settings = new Settings('czk', $setting1, $setting2);

        self::assertSame($privateKey1, $settings->getPrivateKey($settings->getDefaultGatewayKey()));
        self::assertSame('123456789', $settings->getPrivateKeyPassword($settings->getDefaultGatewayKey()));
        self::assertSame($publicKey1, $settings->getPublicKey($settings->getDefaultGatewayKey()));
        self::assertSame('http://localhost', $settings->getUrl($settings->getDefaultGatewayKey()));
        self::assertSame($merchantNumber1, $settings->getMerchantNumber($settings->getDefaultGatewayKey()));
        self::assertSame($depositFlag1, $settings->getDepositFlag($settings->getDefaultGatewayKey()));

        self::assertSame($privateKey1, $settings->getPrivateKey('czk'));
        self::assertSame('123456789', $settings->getPrivateKeyPassword('czk'));
        self::assertSame($publicKey1, $settings->getPublicKey('czk'));
        self::assertSame('http://localhost', $settings->getUrl('czk'));
        self::assertSame($merchantNumber1, $settings->getMerchantNumber('czk'));
        self::assertSame($depositFlag1, $settings->getDepositFlag('czk'));

        self::assertSame($privateKey2, $settings->getPrivateKey('eur'));
        self::assertSame('1234567890', $settings->getPrivateKeyPassword('eur'));
        self::assertSame($publicKey2, $settings->getPublicKey('eur'));
        self::assertSame('http://local', $settings->getUrl('eur'));
        self::assertSame($merchantNumber2, $settings->getMerchantNumber('eur'));
        self::assertSame($depositFlag2, $settings->getDepositFlag('eur'));

    }
}
