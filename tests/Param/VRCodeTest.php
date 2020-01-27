<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\VRCode;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class VRCodeTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.dev>
 */
class VRCodeTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $token = new VRCode('vrcode');

        self::assertSame('vrcode', (string)$token);
        self::assertSame('vrcode', $token->getValue());
        self::assertSame(Param::VRCODE, $token->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('VRCODE max. length is 48! "300" given.');

        new VRCode(TestHelpers::getLongText300());
    }
}
