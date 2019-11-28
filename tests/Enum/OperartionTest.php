<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Enum;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Operation;
use UnexpectedValueException;

class OperartionTest extends TestCase
{

    public function testYES(): void
    {
        $operation = Operation::CREATE_ORDER();

        self::assertSame('CREATE_ORDER', (string)$operation);
        self::assertSame('CREATE_ORDER', $operation->getValue());
    }


    /**
     * @throws UnexpectedValueException
     */
    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage("Value '10' is not part of the enum Pixidos\GPWebPay\Enum\Operation");

        new Operation(10);
    }

}
