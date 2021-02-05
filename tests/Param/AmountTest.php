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
}
