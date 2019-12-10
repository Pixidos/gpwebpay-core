<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Tests\Settings;

use PHPStan\Testing\TestCase;
use Pixidos\GPWebPay\Enum\DepositFlag;
use Pixidos\GPWebPay\Settings\SettingsFactory;

class SettingsFactoryTest extends TestCase
{

    private const CZK = 'czk';
    private const EUR = 'eur';

    public function testSuccessCreate(): void
    {
        $settings = SettingsFactory::create(
            [
                self::CZK => __DIR__ . '/_certs/test.pem',
                self::EUR => __DIR__ . '/_certs/test2.pem'
            ],
            [
                self::CZK => '1234567',
                self::EUR => '12345678'
            ],
            [
                self::CZK => __DIR__ . '/_certs/test-pub.pem',
                self::EUR => __DIR__ . '/_certs/test-pub2.pem',
            ],
            'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            [
                self::CZK => '123456789',
                self::EUR => '123456780'
            ],
            [
                self::CZK => 0,
                self::EUR => 1
            ],
            self::CZK
        );

        self::assertSame(self::CZK, $settings->getDefaultGatewayKey());
        self::assertSame(DepositFlag::NO(), $settings->getDepositFlag(self::CZK)->getValue());
    }
}
