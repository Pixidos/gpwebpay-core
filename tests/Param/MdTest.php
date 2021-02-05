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
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class MdTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author  Ondra Votava <ondra@votava.dev>
 */
class MdTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $md = new Md('md');

        self::assertSame('md', (string)$md);
        self::assertSame('md', $md->getValue());
        self::assertSame(Param::MD, $md->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('MD max. length is 255! "300" given.');

        new Md(TestHelpers::getLongText300());
    }
}
