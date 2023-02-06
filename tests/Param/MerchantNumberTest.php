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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class MerchantNumberTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author  Ondra Votava <ondra@votava.dev>
 */
class MerchantNumberTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $merchantNumber = new MerchantNumber('FA12345678');

        self::assertSame('FA12345678', (string)$merchantNumber);
        self::assertSame('FA12345678', $merchantNumber->getValue());
        self::assertSame(Param::MERCHANTNUMBER, $merchantNumber->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('MERCHANTNUMBER max. length is 10! "300" given.');

        new MerchantNumber(TestHelpers::getLongText300());
    }
}
