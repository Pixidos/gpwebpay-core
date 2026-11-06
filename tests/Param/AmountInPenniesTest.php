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
use Pixidos\GPWebPay\Param\AmountInPennies;

class AmountInPenniesTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $amount = new AmountInPennies(100000);

        self::assertSame(100000, $amount->getValue());
        self::assertSame('100000', (string)$amount);
    }

    public function testZeroIsAcceptedBecauseItDependsOnTheOperation(): void
    {
        self::assertSame('0', (string)new AmountInPennies(0));
    }

    public function testLargestFifteenDigitAmount(): void
    {
        self::assertSame('999999999999999', (string)new AmountInPennies(999999999999999));
    }

    /**
     * @dataProvider invalidAmounts
     */
    public function testInvalidAmountThrowException(int $amount): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('AMOUNT must be an integer from 0 to 999999999999999');

        new AmountInPennies($amount);
    }

    /**
     * @return array<string, array{int}>
     */
    public static function invalidAmounts(): array
    {
        return [
            'negative' => [-500],
            'sixteen digits' => [1000000000000000],
            'max int' => [PHP_INT_MAX],
        ];
    }
}
