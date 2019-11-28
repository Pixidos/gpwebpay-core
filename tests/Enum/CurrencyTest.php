<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Currency;
use UnexpectedValueException;

class CurrencyTest extends TestCase
{

    public function testCZK(): void
    {
        $currency = Currency::CZK();

        self::assertSame('203', (string)$currency);
        self::assertSame('203', $currency->getValue());
    }

    public function testEUR(): void
    {
        $currency = Currency::EUR();

        self::assertSame('978', (string)$currency);
        self::assertSame('978', $currency->getValue());
    }

    /**
     * @dataProvider getCurrency
     *
     * @param string $value
     *
     * @throws UnexpectedValueException
     */
    public function testSuccessCreateByValue(string $value): void
    {
        $currency = new Currency($value);

        self::assertSame((string)$value, (string)$currency);
        self::assertSame($value, $currency->getValue());
    }

    /**
     * @throws UnexpectedValueException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value '10' is not part of the enum Pixidos\GPWebPay\Enum\Currency");

        new Currency('10');
    }

    /**
     * @return Generator
     */
    public function getCurrency(): Generator
    {
        yield ['203'];
        yield ['978'];
        yield [Currency::AWG];
    }
}
