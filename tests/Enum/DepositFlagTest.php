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

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use Grifart\Enum\MissingValueDeclarationException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\DepositFlag;

class DepositFlagTest extends TestCase
{
    public function testYES(): void
    {
        $depositFlag = DepositFlag::YES();

        self::assertSame('1', (string)$depositFlag);
        self::assertSame(1, $depositFlag->toScalar());
    }

    public function testNO(): void
    {
        $depositFlag = DepositFlag::NO();

        self::assertSame('0', (string)$depositFlag);
        self::assertSame(0, $depositFlag->toScalar());
    }

    /**
     * @dataProvider getDepositFlag
     *
     * @param int $value
     */
    public function testSuccessCreateByValue(int $value): void
    {
        $depositFlag = DepositFlag::fromScalar($value);

        self::assertSame((string)$value, (string)$depositFlag);
        self::assertSame($value, $depositFlag->toScalar());
    }


    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(MissingValueDeclarationException::class);
        $this->expectExceptionMessage(
            "There is no value for enum 'Pixidos\GPWebPay\Enum\DepositFlag' and scalar value '10'."
        );

        DepositFlag::fromScalar(10);
    }

    /**
     * @return Generator<array<int, int>>
     */
    public function getDepositFlag(): Generator
    {
        yield [1];
        yield [0];
    }
}
