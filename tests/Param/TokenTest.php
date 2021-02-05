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
use Pixidos\GPWebPay\Param\Token;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class TokenTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author  Ondra Votava <ondra@votava.dev>
 */
class TokenTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $token = new Token('token');

        self::assertSame('token', (string)$token);
        self::assertSame('token', $token->getValue());
        self::assertSame(Param::TOKEN, $token->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('TOKEN max. length is 64! "300" given.');

        new Token(TestHelpers::getLongText300());
    }
}
