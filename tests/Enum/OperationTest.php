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

use Grifart\Enum\MissingValueDeclarationException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Operation;

class OperationTest extends TestCase
{
    public function testCreateOrder(): void
    {
        $operation = Operation::CREATE_ORDER();

        self::assertSame('CREATE_ORDER', (string)$operation);
        self::assertSame('CREATE_ORDER', $operation->toScalar());
    }


    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(MissingValueDeclarationException::class);
        $this->expectExceptionMessage(
            "There is no value for enum 'Pixidos\GPWebPay\Enum\Operation' and scalar value '10'."
        );

        Operation::fromScalar(10);
    }
}
