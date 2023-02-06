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

namespace Pixidos\GPWebPay\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\PayMethod;
use Pixidos\GPWebPay\Tests\TestHelpers;

class RequestTest extends TestCase
{
    public function testSortParams(): void
    {
        $operation = TestHelpers::createOperation();
        $request = new Request(
            $operation,
            new MerchantNumber('123456789'),
            new DepositFlag(\Pixidos\GPWebPay\Enum\DepositFlag::YES()),
            'https://example.com/sucesss'
        );

        $request->setParam(new PayMethod(PayMethodEnum::CARD()));

        $request->sortParams();

        self::assertSame(
            [
                Param::MERCHANTNUMBER,
                Param::OPERATION,
                Param::ORDERNUMBER,
                Param::AMOUNT,
                Param::CURRENCY,
                Param::DEPOSITFLAG,
                Param::RESPONSE_URL,
                Param::MD,
                Param::PAYMETHOD,
            ],
            array_keys($request->getParams())
        );
    }
}
