<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use UnexpectedValueException;

class ParamTest extends TestCase
{

    public function testUSERPARAM(): void
    {
        $Param = Param::USERPARAM();

        self::assertSame('USERPARAM1', (string)$Param);
        self::assertSame('USERPARAM1', $Param->getValue());
    }

    public function testRESPONSE_URL(): void
    {
        $Param = Param::RESPONSE_URL();

        self::assertSame('URL', (string)$Param);
        self::assertSame('URL', $Param->getValue());
    }

    /**
     * @dataProvider getCurrency
     *
     * @param string $value
     *
     * @throws UnexpectedValueException
     */
    public function testSuccessCreateByValue(string $value): void
    {
        $Param = new Param($value);

        self::assertSame($value, (string)$Param);
        self::assertSame($value, $Param->getValue());
    }

    /**
     * @throws UnexpectedValueException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value 'CARD' is not part of the enum Pixidos\GPWebPay\Enum\Param");

        new Param('CARD');
    }

    /**
     * @return Generator
     */
    public function getCurrency(): Generator
    {
        yield [Param::MERORDERNUM];
        yield [Param::ORDERNUMBER];
        yield [Param::OPERATION];
        yield [Param::RESPONSE_URL];
        yield [Param::USERPARAM];
        yield [Param::MD];
        yield [Param::AMOUNT];
        yield [Param::FASTPAYID];
        yield [Param::CURRENCY];
        yield [Param::DEPOSITFLAG];
        yield [Param::DESCRIPTION];
        yield [Param::REFERENCENUMBER];
        yield [Param::EMAIL];
        yield [Param::DIGEST];
        yield [Param::PAYMETHOD];
        yield [Param::PAYMETHODS];
        yield [Param::DISABLEPAYMETHOD];
        yield [Param::ADDINFO];
        yield [Param::LANG];
        yield [Param::TOKEN];
        yield [Param::FAST_TOKEN];
    }
}
