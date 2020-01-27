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

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use Grifart\Enum\MissingValueDeclarationException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\PayMethod;

class PayMethodTest extends TestCase
{

    public function testCard(): void
    {
        $payMethod = PayMethod::CARD();

        self::assertSame('CRD', (string)$payMethod);
        self::assertSame('CRD', $payMethod->toScalar());
    }

    public function testGOOGLE_PAY(): void
    {
        $payMethod = PayMethod::GOOGLE_PAY();

        self::assertSame('GPAY', (string)$payMethod);
        self::assertSame('GPAY', $payMethod->toScalar());
    }

    /**
     * @dataProvider getCurrency
     *
     * @param string $value
     *
     */
    public function testSuccessCreateByValue(string $value): void
    {
        $payMethod = PayMethod::fromScalar($value);

        self::assertSame($value, (string)$payMethod);
        self::assertSame($value, $payMethod->toScalar());
    }

    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(MissingValueDeclarationException::class);
        $this->expectExceptionMessage("There is no value for enum 'Pixidos\GPWebPay\Enum\PayMethod' and scalar value 'CARD'.");

        PayMethod::fromScalar('CARD');
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
