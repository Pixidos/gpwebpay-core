<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod;
use Pixidos\GPWebPay\Param\DisablePayMethod;

class DisablePayMethodTest extends TestCase
{

    public function testCreate(): void
    {
        $payMethod = new DisablePayMethod(PayMethod::CARD());

        self::assertEquals(PayMethod::CARD(), $payMethod->getValue());
        self::assertSame('CRD', (string)$payMethod);
        self::assertSame(Param::DISABLEPAYMETHOD, $payMethod->getParamName());
    }
}
