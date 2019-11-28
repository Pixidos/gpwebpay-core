<?php declare(strict_types=1);

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
