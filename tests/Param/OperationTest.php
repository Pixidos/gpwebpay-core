<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Operation;

class OperationTest extends TestCase
{


    public function testSuccessCreate(): void
    {
        $amount = new Operation(OperationEnum::CREATE_ORDER());

        self::assertEquals(OperationEnum::CREATE_ORDER(), $amount->getValue());
        self::assertSame('CREATE_ORDER', (string)$amount);
        self::assertSame(Param::OPERATION, $amount->getParamName());
    }

}
