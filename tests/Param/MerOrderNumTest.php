<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\MerOrderNum;

class MerOrderNumTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $merOrderNum = new MerOrderNum(123455);

        self::assertSame(123455, $merOrderNum->getValue());
        self::assertSame('123455', (string)$merOrderNum);
        self::assertSame(Param::MERORDERNUM, $merOrderNum->getParamName());
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
        $this->expectExceptionMessage(sprintf('MERORDERNUM must be integer "%s" given.', $value));

        new MerOrderNum($value);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTooLongValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('MERORDERNUM max. length is 30! "31" given.');

        new MerOrderNum('1234567890123456789012345678901');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testInvalidScalarValueThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('MERORDERNUM must be numeric scalar type "string" given.');

        new MerOrderNum('FAU1');
    }

    public function getInvalidValue(): Generator
    {
        yield ['1.0'];
        yield [10.01];
    }
}
