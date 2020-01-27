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
use Pixidos\GPWebPay\Param\ReferenceNumber;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class ReferenceNumberTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.dev>
 */
class ReferenceNumberTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $referenceNumber = new ReferenceNumber('REF123456789');

        self::assertSame('REF123456789', (string)$referenceNumber);
        self::assertSame('REF123456789', $referenceNumber->getValue());
        self::assertSame(Param::REFERENCENUMBER, $referenceNumber->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('REFERENCENUMBER has invalid lenght(max 20) or containt invalid char. Alowed chars are(0-9A-Za-z(space)#*+,-./:;=@^_).');

        new ReferenceNumber(TestHelpers::getLongText300());
    }
}
