<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Description;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class DescriptionTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class DescriptionTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $description = new Description('description');

        self::assertSame('description', (string)$description);
        self::assertSame('description', $description->getValue());
        self::assertSame(Param::DESCRIPTION, $description->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DESCRIPTION max. length is 255! "300" given.');

        new Description(TestHelpers::getLongText300());
    }
}
