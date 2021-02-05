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

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\PayMethod;

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
