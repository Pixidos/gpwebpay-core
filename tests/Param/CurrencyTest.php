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

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Currency;

class CurrencyTest extends TestCase
{


    public function testSuccessCreate(): void
    {
        $currency = new Currency(\Pixidos\GPWebPay\Enum\Currency::CZK());

        self::assertSame('203', (string)$currency);
        self::assertEquals(\Pixidos\GPWebPay\Enum\Currency::CZK(), $currency->getValue());
        self::assertSame(Param::CURRENCY, $currency->getParamName());
    }
}
