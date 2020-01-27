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
use PHPUnit\Framework\TestCase;

class SettingTest extends TestCase
{

    public function testCreate(): void
    {
        $privateKey = __DIR__ . '/../_certs/test.pem';
        $publicKey = __DIR__ . '/../_certs/test-pub.pem';
        $merchantNumber = new MerchantNumber('1234567890');
        $depositFlag = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES());

        $setting = new Setting(
            $privateKey,
            '123456789',
            $publicKey,
            'http://localhost',
            $merchantNumber,
            $depositFlag,
            'czk'
        );

        self::assertSame($privateKey, $setting->getPrivateKey());
        self::assertSame('123456789', $setting->getPrivateKeyPassword());
        self::assertSame($publicKey, $setting->getPublicKey());
        self::assertSame('http://localhost', $setting->getUrl());
        self::assertSame($merchantNumber, $setting->getMerchantNumber());
        self::assertSame($depositFlag, $setting->getDepositFlag());
    }
}
