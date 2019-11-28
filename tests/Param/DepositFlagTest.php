<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\DepositFlag as DepositFlagEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\DepositFlag;

class DepositFlagTest extends TestCase
{


    public function testSuccessCreate(): void
    {
        $depositFlag = new DepositFlag(DepositFlagEnum::YES());

        self::assertSame('1', (string)$depositFlag);
        self::assertEquals(DepositFlagEnum::YES(), $depositFlag->getValue());
        self::assertSame(Param::DEPOSITFLAG, $depositFlag->getParamName());
    }


}
