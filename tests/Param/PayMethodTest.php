<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\PayMethod;
use PHPUnit\Framework\TestCase;

class PayMethodTest extends TestCase
{

    public function testCreate(): void
    {
        $payMethod = new PayMethod(\Pixidos\GPWebPay\Enum\PayMethod::CARD());

        self::assertEquals(\Pixidos\GPWebPay\Enum\PayMethod::CARD(), $payMethod->getValue());
        self::assertSame('CRD', (string)$payMethod);
        self::assertSame(Param::PAYMETHOD, $payMethod->getParamName());
    }
}
