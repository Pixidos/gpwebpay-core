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

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use Grifart\Enum\MissingValueDeclarationException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Currency;
use UnexpectedValueException;

class CurrencyTest extends TestCase
{

    public function testCZK(): void
    {
        $currency = Currency::CZK();

        self::assertSame('203', (string)$currency);
        self::assertSame('203', $currency->toScalar());
    }

    public function testEUR(): void
    {
        $currency = Currency::EUR();

        self::assertSame('978', (string)$currency);
        self::assertSame('978', $currency->toScalar());
    }

    /**
     * @dataProvider getCurrency
     *
     * @param string $value
     *
     * @throws MissingValueDeclarationException
     */
    public function testSuccessCreateByValue(string $value): void
    {
        $currency = Currency::fromScalar($value);

        self::assertSame($value, (string)$currency);
        self::assertSame($value, $currency->toScalar());
    }

    /**
     * @throws MissingValueDeclarationException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(MissingValueDeclarationException::class);
        $this->expectExceptionMessage("There is no value for enum 'Pixidos\GPWebPay\Enum\Currency' and scalar value '10'.");

        Currency::fromScalar('10');
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
