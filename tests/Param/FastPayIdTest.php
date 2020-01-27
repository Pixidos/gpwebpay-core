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

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\FastPayId;

class FastPayIdTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $fastPayId = new FastPayId(123455);

        self::assertSame(123455, $fastPayId->getValue());
        self::assertSame('123455', (string)$fastPayId);
        self::assertSame(Param::FASTPAYID, $fastPayId->getParamName());
    }


    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     * @dataProvider getInvalidValue
     */
    public function testInvalidValueThrowException($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('FASTPAYID must be integer "%s" given.', $value));

        new FastPayId($value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTooLongValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FASTPAYID max. length is 15! "16" given.');

        new FastPayId('1234567890123456');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testInvalidScalarValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FASTPAYID must be numeric scalar type "string" given.');

        new FastPayId('FAU1');
    }

    public function getInvalidValue(): Generator
    {
        yield ['1.0'];
        yield [10.01];
    }
}
