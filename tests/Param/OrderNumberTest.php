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

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\OrderNumber;

class OrderNumberTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $merOrderNum = new OrderNumber(123455);

        self::assertSame(123455, $merOrderNum->getValue());
        self::assertSame('123455', (string)$merOrderNum);
        self::assertSame(Param::ORDERNUMBER, $merOrderNum->getParamName());
    }


    /**
     * @param string|int|float $value
     *
     * @throws InvalidArgumentException
     * @dataProvider getInvalidValue
     */
    public function testInvalidValueThrowException($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('ORDERNUMBER must be integer "%s" given.', $value));

        new OrderNumber($value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTooLongValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ORDERNUMBER max. length is 15! "31" given.');

        new OrderNumber('1234567890123456789012345678901');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testInvalidScalarValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ORDERNUMBER must be numeric scalar type "string" given.');

        new OrderNumber('FAU1');
    }

    /**
     * @return Generator<mixed>
     */
    public function getInvalidValue(): Generator
    {
        yield ['1.0'];
        yield [10.01];
    }
}
