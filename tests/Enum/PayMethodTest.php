<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\PayMethod;
use UnexpectedValueException;

class PayMethodTest extends TestCase
{

    public function testCard(): void
    {
        $payMethod = PayMethod::CARD();

        self::assertSame('CRD', (string)$payMethod);
        self::assertSame('CRD', $payMethod->getValue());
    }

    public function testGOOGLE_PAY(): void
    {
        $payMethod = PayMethod::GOOGLE_PAY();

        self::assertSame('GPAY', (string)$payMethod);
        self::assertSame('GPAY', $payMethod->getValue());
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
        $payMethod = new PayMethod($value);

        self::assertSame($value, (string)$payMethod);
        self::assertSame($value, $payMethod->getValue());
    }

    /**
     * @throws UnexpectedValueException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value 'CARD' is not part of the enum Pixidos\GPWebPay\Enum\PayMethod");

        new PayMethod('CARD');
    }

    /**
     * @return Generator
     */
    public function getCurrency(): Generator
    {
        yield [PayMethod::CARD];
        yield [PayMethod::MASTERCARD_MOBILE];
        yield [PayMethod::MASTERPASS];
        yield [PayMethod::PLATBA24];
        yield [PayMethod::BANK_ACCOUNT];
        yield [PayMethod::BANK_KB];
        yield [PayMethod::BANK_CSOB];
        yield [PayMethod::BANK_CNB];
        yield [PayMethod::BANK_CS];
        yield [PayMethod::BANK_FIO];
        yield [PayMethod::BANK_UCB];
        yield [PayMethod::BANK_RB];
        yield [PayMethod::BANK_MBANK];
        yield [PayMethod::BANK_SBERBANK];
        yield [PayMethod::BANK_AIRB];
    }
}
