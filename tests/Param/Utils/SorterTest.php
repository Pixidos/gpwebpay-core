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

namespace Pixidos\GPWebPay\Tests\Param\Utils;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Utils\Sorter;

class SorterTest extends TestCase
{
    public function testSortRequestParams(): void
    {
        $params = [
            Param::ORDERNUMBER => new FakeParam('someparam'),
            Param::OPERATION => new FakeParam('someparam'),
            Param::MERCHANTNUMBER => new FakeParam('someparam'),
            Param::DEPOSITFLAG => new FakeParam('someparam'),
            Param::AMOUNT => new FakeParam('someparam'),
            Param::CURRENCY => new FakeParam('someparam'),
            Param::MERORDERNUM => new FakeParam('someparam'),
            Param::DESCRIPTION => new FakeParam('someparam'),
            Param::RESPONSE_URL => new FakeParam('someparam'),
            Param::MD => new FakeParam('someparam'),
            Param::USERPARAM => new FakeParam('someparam'),
            Param::FASTPAYID => new FakeParam('someparam'),
            Param::VRCODE => new FakeParam('someparam'),
            Param::DISABLEPAYMETHOD => new FakeParam('someparam'),
            Param::PAYMETHOD => new FakeParam('someparam'),
            Param::EMAIL => new FakeParam('someparam'),
            Param::PAYMETHODS => new FakeParam('someparam'),
            Param::REFERENCENUMBER => new FakeParam('someparam'),
            Param::ADDINFO => new FakeParam('someparam'),
            Param::PANPATTERN => new FakeParam('someparam'),
            Param::TOKEN => new FakeParam('someparam'),
            Param::FAST_TOKEN => new FakeParam('someparam'),
            Param::LANG => new FakeParam('someparam'),
            Param::DIGEST => new FakeParam('someparam'),
        ];

        $sorted = Sorter::sortRequestParams($params);

        self::assertSame(Sorter::REQUEST_PARAM_ORDER, array_keys($sorted));
    }

    public function testSortResponseParams(): void
    {
        $params = [
            Param::MERORDERNUM => new FakeParam('someparam'),
            Param::ORDERNUMBER => new FakeParam('someparam'),
            Response::PRCODE => new FakeParam('someparam'),
            Response::SRCODE => new FakeParam('someparam'),
            Param::MD => new FakeParam('someparam'),
            Param::USERPARAM => new FakeParam('someparam'),
            Param::ADDINFO => new FakeParam('someparam'),
            Response::RESULTTEXT => new FakeParam('someparam'),
            Response::EXPIRY => new FakeParam('someparam'),
            Param::TOKEN => new FakeParam('someparam'),
            Param::OPERATION => new FakeParam('someparam'),
            Response::ACSRES => new FakeParam('someparam'),
            Response::ACCODE => new FakeParam('someparam'),
            Response::DIGEST1 => new FakeParam('someparam'),
            Param::PANPATTERN => new FakeParam('someparam'),
            Response::DAYTOCAPTURE => new FakeParam('someparam'),
            Response::TOKENREGSTATUS => new FakeParam('someparam'),
            Param::DIGEST => new FakeParam('someparam'),
        ];

        $sorted = Sorter::sortResponseParams($params);

        self::assertSame(Sorter::RESPONSE_PARAM_ORDER, array_keys($sorted));
    }
}
