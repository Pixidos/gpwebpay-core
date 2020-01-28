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

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Operation;

class OperationTest extends TestCase
{


    public function testSuccessCreate(): void
    {
        $operation = new Operation(OperationEnum::CREATE_ORDER());

        self::assertEquals(OperationEnum::CREATE_ORDER(), $operation->getValue());
        self::assertSame('CREATE_ORDER', (string)$operation);
        self::assertSame(Param::OPERATION, $operation->getParamName());
    }

}
