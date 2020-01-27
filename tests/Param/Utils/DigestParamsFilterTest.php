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

namespace Pixidos\GPWebPay\Tests\Param\Utils;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Utils\DigestParamsFilter;
use PHPUnit\Framework\TestCase;

class DigestParamsFilterTest extends TestCase
{

    public function testFilter(): void
    {
        $params =
            [
                Param::ORDERNUMBER => 'someparam',
                Param::OPERATION => 'someparam',
                Param::MERCHANTNUMBER => 'someparam',
                Param::DEPOSITFLAG => 'someparam',
                Param::AMOUNT => 'someparam',
                Param::CURRENCY => 'someparam',
                Param::MERORDERNUM => 'someparam',
                Param::DESCRIPTION => 'someparam',
                Param::RESPONSE_URL => 'someparam',
                Param::MD => 'someparam',
                Param::USERPARAM => 'someparam',
                Param::FASTPAYID => 'someparam',
                Param::VRCODE => 'someparam',
                Param::DISABLEPAYMETHOD => 'someparam',
                Param::PAYMETHOD => 'someparam',
                Param::EMAIL => 'someparam',
                Param::PAYMETHODS => 'someparam',
                Param::REFERENCENUMBER => 'someparam',
                Param::ADDINFO => 'someparam',
                Param::PANPATTERN => 'someparam',
                Param::TOKEN => 'someparam',
                Param::FAST_TOKEN => 'someparam',
                Param::LANG => 'someparam',
                Param::DIGEST => 'someparam',
            ];

        $filtered = DigestParamsFilter::filter($params);
        self::assertSame($this->getExpectedKeys(), array_keys($filtered));
    }

    private function getExpectedKeys(): array
    {
        return [
            Param::ORDERNUMBER,
            Param::OPERATION,
            Param::MERCHANTNUMBER,
            Param::DEPOSITFLAG,
            Param::AMOUNT,
            Param::CURRENCY,
            Param::MERORDERNUM,
            Param::DESCRIPTION,
            Param::RESPONSE_URL,
            Param::MD,
            Param::USERPARAM,
            Param::FASTPAYID,
            Param::DISABLEPAYMETHOD,
            Param::PAYMETHOD,
            Param::EMAIL,
            Param::PAYMETHODS,
            Param::REFERENCENUMBER,
            Param::ADDINFO,
            Param::TOKEN,
        ];
    }
}
