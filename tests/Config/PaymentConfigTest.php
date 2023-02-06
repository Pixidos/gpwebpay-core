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
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;

class PaymentConfigTest extends TestCase
{
    public function testCreate(): void
    {
        $merchantNumber = new MerchantNumber('1234567890');
        $depositFlag = new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES());
        $responseUrl = new ResponseUrl('http://example.com');
        $setting = new PaymentConfig(
            'http://localhost',
            $merchantNumber,
            $depositFlag,
            'czk',
            $responseUrl
        );

        self::assertSame('http://localhost', $setting->getUrl());
        self::assertSame($merchantNumber, $setting->getMerchantNumber());
        self::assertSame($depositFlag, $setting->getDepositFlag());
        self::assertSame('czk', $setting->getGateway());
        self::assertSame($responseUrl, $setting->getResponseUrl());
    }
}
