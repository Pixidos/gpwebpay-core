<?php

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

declare(strict_types=1);

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

    public function testGooglePay(): void
    {
        $payMethod = PayMethod::GOOGLE_PAY();

        self::assertSame('GPAY', (string)$payMethod);
        self::assertSame('GPAY', $payMethod->toScalar());
    }

    /**
     * @dataProvider getPayMethod
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
        $this->expectExceptionMessage(
            "There is no value for enum 'Pixidos\GPWebPay\Enum\PayMethod' and scalar value 'CARD'."
        );

        PayMethod::fromScalar('CARD');
    }

    /**
     * Current values (HTTP API 1.19, appendix 5) and legacy values kept for compatibility.
     *
     * @return Generator<array<int, string>>
     */
    public function getPayMethod(): Generator
    {
        $values = [
            'CRD', 'GPAY', 'APAY', 'PAYPAL', 'CTP', 'APM-BTR',
            'APM-BCCS', 'APM-BCKB', 'APM-BCOB', 'APM-BCRB', 'APM-BCMB', 'APM-BCFI', 'APM-BCMO', 'APM-BCAI', 'APM-BCQR',
            'LCTD', 'LCTT', 'APM-BAEB',
            'APM-BSSS', 'APM-BSTB', 'APM-BSVB', 'APM-BSOB', 'APM-BSPR', 'APM-BSQR',
            'BTNCS', 'SOFORT', 'EPS', 'PAYSAFECARD', 'SEPADIRECTDEBIT', 'KLARNA',
            // legacy
            'MCM', 'MPS', 'BTN360CS', 'BTN360CS-0100', 'BTN360CS-0300', 'BTN360CS-0600', 'BTN360CS-0710',
            'BTN360CS-0800', 'BTN360CS-2010', 'BTN360CS-2700', 'BTN360CS-3030', 'BTN360CS-5500', 'BTN360CS-6210',
            'BTN360CS-6800',
        ];
        foreach ($values as $value) {
            yield [$value];
        }
    }

    public function testNewMethods(): void
    {
        self::assertSame('PAYPAL', (string)PayMethod::PAYPAL());
        self::assertSame('CTP', (string)PayMethod::CLICK_TO_PAY());
        self::assertSame('APM-BTR', (string)PayMethod::APM_ALL());
        self::assertSame('LCTD', (string)PayMethod::TWISTO_PAY_IN_30_DAYS());
        self::assertSame('LCTT', (string)PayMethod::TWISTO_PAY_IN_THIRDS());
        self::assertSame('APM-BSQR', (string)PayMethod::APM_SK_QR());
    }
}
