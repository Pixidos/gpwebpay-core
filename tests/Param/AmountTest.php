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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Amount;

class AmountTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $amount = new Amount(1000);

        self::assertSame(100000, $amount->getValue());
        self::assertSame('100000', (string)$amount);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreateWithoutConverting(): void
    {
        $amount = new Amount(1000, false);

        self::assertSame(1000, $amount->getValue());
        self::assertSame('1000', (string)$amount);
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testNonIntegerThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('AMOUNT must be integer "100000.1" given.');

        new Amount(1000.001);
    }

    /**
     * @dataProvider acceptedAmounts
     */
    public function testAmountIsConvertedExactly(float $amount, bool $convert, int $expected): void
    {
        self::assertSame($expected, (new Amount($amount, $convert))->getValue());
    }

    /**
     * @return array<string, array{float, bool, int}>
     */
    public static function acceptedAmounts(): array
    {
        return [
            '19.99' => [19.99, true, 1999],
            '0.29' => [0.29, true, 29],
            '1.15' => [1.15, true, 115],
            '4.35' => [4.35, true, 435],
            '0.01' => [0.01, true, 1],
            '100.10' => [100.10, true, 10010],
            // results of arithmetic, one or two ULPs from the nearest double of the price
            '0.1 + 0.2' => [0.1 + 0.2, true, 30],
            '0.7 + 0.1' => [0.7 + 0.1, true, 80],
            '19.99 * 3' => [19.99 * 3, true, 5997],
            'two ULPs above 0.30' => [self::ulps(0.3, 2), true, 30],
            'two ULPs below 19.99' => [self::ulps(19.99, -2), true, 1999],
            // two ULPs (7.5e-9) are below 1e-6 of a heller (1e-8) up to 2^25
            'two ULPs above 30000000.01' => [self::ulps(30000000.01, 2), true, 3000000001],
            'one ULP above 40000000.01' => [self::ulps(40000000.01, 1), true, 4000000001],
            'exact 100000000.01' => [100000000.01, true, 10000000001],
            'exact 1000000000000.00' => [1000000000000.0, true, 100000000000000],
            'largest amount' => [9999999999999.99, true, 999999999999999],
            'already in pennies' => [1000.0, false, 1000],
            'pennies computed as 19.99 * 100' => [19.99 * 100, false, 1999],
            'one ULP above 4000000000 pennies' => [self::ulps(4000000000.0, 1), false, 4000000000],
            'largest amount in pennies' => [999999999999999.0, false, 999999999999999],
        ];
    }

    public function testEveryCentFromOneCentToThousandIsConvertedExactly(): void
    {
        $wrong = [];
        for ($cents = 1; $cents <= 100000; ++$cents) {
            $value = (new Amount($cents / 100))->getValue();
            if ($value !== $cents) {
                $wrong[] = sprintf('%d -> %d', $cents, $value);
            }
        }

        self::assertSame([], $wrong);
    }

    /**
     * @dataProvider invalidAmounts
     */
    public function testInvalidAmountThrowException(float $amount, bool $convert, string $message): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        new Amount($amount, $convert);
    }

    /**
     * @return array<string, array{float, bool, string}>
     */
    public static function invalidAmounts(): array
    {
        $precision = 'AMOUNT must be integer';
        $length = 'AMOUNT max. length is 15!';

        return [
            'three decimals' => [0.005, true, $precision],
            'tiny fraction' => [0.00001, true, $precision],
            'fraction without conversion' => [12.5, false, 'AMOUNT must be integer "12.5" given.'],
            'zero' => [0.0, true, 'AMOUNT must be integer "0" given.'],
            'negative' => [-5.5, true, 'AMOUNT must be integer "-550" given.'],
            'infinite' => [INF, true, 'AMOUNT must be integer "INF" given.'],
            'not a number' => [NAN, true, 'AMOUNT must be integer "NAN" given.'],
            // more precise than a heller although each is a distinct double
            'three ULPs above 0.30' => [self::ulps(0.3, 3), true, $precision],
            'two ULPs above 40000000.01 (1.5e-8)' => [self::ulps(40000000.01, 2), true, $precision],
            'one ULP above 100000000.01 (1.5e-8)' => [self::ulps(100000000.01, 1), true, $precision],
            '1000000000000.001' => [1000000000000.001, true, $precision],
            'one ULP above 1000000000000.00' => [self::ulps(1000000000000.0, 1), true, $precision],
            'three ULPs below 1999 pennies' => [self::ulps(1999.0, -3), false, $precision],
            'two ULPs above 5000000000 pennies' => [self::ulps(5000000000.0, 2), false, $precision],
            'one ULP above 10000000000 pennies' => [self::ulps(10000000000.0, 1), false, $precision],
            'half a penny at the top of the range' => [999999999999998.5, false, $precision],
            'one ULP (1/8) above 999999999999998 pennies' => [self::ulps(999999999999998.0, 1), false, $precision],
            // more than 15 digits, rejected before rounding
            'one ULP above the largest amount' => [self::ulps(9999999999999.99, 1), true, $length],
            'sixteen digits' => [10000000000000.0, true, $length],
            'above the largest amount in pennies' => [999999999999999.4, false, $length],
            'one ULP above the largest amount in pennies' => [self::ulps(999999999999999.0, 1), false, $length],
            'sixteen digits without conversion' => [1000000000000000.0, false, $length],
            'exponent notation' => [1e20, true, $length],
        ];
    }

    /**
     * The double $steps representable values away from $value (positive finite values only).
     */
    private static function ulps(float $value, int $steps): float
    {
        $bits = unpack('q', pack('d', $value));
        self::assertIsArray($bits);
        self::assertIsInt($bits[1]);
        $result = unpack('d', pack('q', $bits[1] + $steps));
        self::assertIsArray($result);
        self::assertIsFloat($result[1]);

        return $result[1];
    }
}
