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
use Pixidos\GPWebPay\Param\UserParam;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class UserParamTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.dev>
 */
class UserParamTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $userParam = new UserParam('userParam');

        self::assertSame('userParam', (string)$userParam);
        self::assertSame('userParam', $userParam->getValue());
        self::assertSame(Param::USERPARAM, $userParam->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('USERPARAM1 max. length is 255! "300" given.');

        new UserParam(TestHelpers::getLongText300());
    }
}
