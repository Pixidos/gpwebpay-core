<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PM;
use Pixidos\GPWebPay\Param\PayMethods;

class PayMethodsTest extends TestCase
{

    public function testCreate(): void
    {
        $payMethod = new PayMethods(PM::CARD(), PM::MASTERPASS(), PM::GOOGLE_PAY());

        self::assertEquals([PM::CARD(), PM::MASTERPASS(), PM::GOOGLE_PAY()], $payMethod->getValue());
        self::assertSame('CRD,MPS,GPAY', (string)$payMethod);
        self::assertSame(Param::PAYMETHODS, $payMethod->getParamName());
    }

    public function testCanAddPayMethodOnlyOneTime(): void
    {
        $payMethod = new PayMethods(
            PM::CARD(),
            PM::MASTERPASS(),
            PM::GOOGLE_PAY(),
            PM::CARD(),
            PM::MASTERPASS(),
            PM::GOOGLE_PAY()
        );

        self::assertEquals([PM::CARD(), PM::MASTERPASS(), PM::GOOGLE_PAY()], $payMethod->getValue());
        self::assertSame('CRD,MPS,GPAY', (string)$payMethod);
    }

    public function testAddMethod(): void
    {
        $payMethod = new PayMethods(
            PM::CARD(),
            PM::MASTERPASS(),
            PM::GOOGLE_PAY()
        );

        self::assertEquals([PM::CARD(), PM::MASTERPASS(), PM::GOOGLE_PAY()], $payMethod->getValue());
        self::assertSame('CRD,MPS,GPAY', (string)$payMethod);

        $payMethod->addMethod(PM::MASTERCARD_MOBILE());
        $payMethod->addMethod(PM::CARD()); //will not added

        self::assertEquals([PM::CARD(), PM::MASTERPASS(), PM::GOOGLE_PAY(), PM::MASTERCARD_MOBILE()], $payMethod->getValue());
        self::assertSame('CRD,MPS,GPAY,MCM', (string)$payMethod);
    }
}
