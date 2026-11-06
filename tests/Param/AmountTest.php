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
     * @dataProvider decimalPrices
     */
    public function testDecimalPriceIsConvertedExactly(float $price, int $expected): void
    {
        self::assertSame($expected, (new Amount($price))->getValue());
    }

    /**
     * @return array<string, array{float, int}>
     */
    public static function decimalPrices(): array
    {
        return [
            '19.99' => [19.99, 1999],
            '0.29' => [0.29, 29],
            '1.15' => [1.15, 115],
            '4.35' => [4.35, 435],
            '0.01' => [0.01, 1],
            '100.10' => [100.10, 10010],
            '0.1 + 0.2' => [0.1 + 0.2, 30],
            'largest 15-digit amount' => [9999999999999.99, 999999999999999],
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
        return [
            'three decimals' => [0.005, true, 'AMOUNT must be integer'],
            'tiny fraction' => [0.00001, true, 'AMOUNT must be integer'],
            'fraction without conversion' => [12.5, false, 'AMOUNT must be integer "12.5" given.'],
            'zero' => [0.0, true, 'AMOUNT must be integer "0" given.'],
            'negative' => [-5.5, true, 'AMOUNT must be integer "-550" given.'],
            'infinite' => [INF, true, 'AMOUNT must be integer "INF" given.'],
            'not a number' => [NAN, true, 'AMOUNT must be integer "NAN" given.'],
            'sixteen digits' => [10000000000000.0, true, 'AMOUNT max. length is 15!'],
            'sixteen digits without conversion' => [1000000000000000.0, false, 'AMOUNT max. length is 15!'],
            'exponent notation' => [1e20, true, 'AMOUNT max. length is 15!'],
        ];
    }
}
