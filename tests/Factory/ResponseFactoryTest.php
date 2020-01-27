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

namespace Pixidos\GPWebPay\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Tests\TestHelpers;

class ResponseFactoryTest extends TestCase
{

    /***/
    public function testCreateResponse(): void
    {
        $factory = new ResponseFactory(TestHelpers::createSettings());
        $response = $factory->create($this->getParams());

        self::assertSame('sometext', $response->getMd());
        self::assertSame(TestHelpers::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(TestHelpers::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame(0, $response->getPrcode());
        self::assertSame(0, $response->getSrcode());
        self::assertSame(TestHelpers::RESULTTEXT, $response->getResultText());
        self::assertSame('czk', $response->getGatewayKey());
        self::assertSame('XXXX', $response->getParams()[Param::TOKEN]->getValue());
        self::assertFalse($response->hasError());
    }


    private function getParams(): array
    {
        return [
            'OPERATION' => 'CREATE_ORDER',
            Param::ORDERNUMBER => TestHelpers::ORDER_NUMBER,
            Param::MERORDERNUM => TestHelpers::MER_ORDER_NUM,
            Param::MD => 'czk|sometext',
            IResponse::PRCODE => TestHelpers::PRCODE,
            IResponse::SRCODE => TestHelpers::SRCODE,
            IResponse::RESULTTEXT => TestHelpers::RESULTTEXT,
            Param::DIGEST => TestHelpers::HASH_1,
            IResponse::DIGEST_1 => TestHelpers::HASH_2,
            Param::TOKEN => 'XXXX',
        ];
    }
}
