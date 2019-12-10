<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

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
