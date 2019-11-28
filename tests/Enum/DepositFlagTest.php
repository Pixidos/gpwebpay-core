<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\DepositFlag;
use UnexpectedValueException;

class DepositFlagTest extends TestCase
{

    public function testYES(): void
    {
        $depositFlag = DepositFlag::YES();

        self::assertSame('1', (string)$depositFlag);
        self::assertSame(1, $depositFlag->getValue());
    }

    public function testNO(): void
    {
        $depositFlag = DepositFlag::NO();

        self::assertSame('0', (string)$depositFlag);
        self::assertSame(0, $depositFlag->getValue());
    }

    /**
     * @dataProvider getDepositFlag
     *
     * @param int $value
     *
     * @throws UnexpectedValueException
     */
    public function testSuccessCreateByValue(int $value): void
    {
        $depositFlag = new DepositFlag($value);

        self::assertSame((string)$value, (string)$depositFlag);
        self::assertSame($value, $depositFlag->getValue());
    }

    /**
     * @throws UnexpectedValueException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value '10' is not part of the enum Pixidos\GPWebPay\Enum\DepositFlag");

        new DepositFlag(10);
    }

    /**
     * @return Generator
     */
    public function getDepositFlag(): Generator
    {
        yield [1];
        yield [0];
    }
}
