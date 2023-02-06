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

namespace Pixidos\GPWebPay\Tests\Factory;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Tests\TestHelpers;

class ResponseFactoryTest extends TestCase
{
    /***/
    public function testCreateResponse(): void
    {
        $factory = TestHelpers::createResponseFactory();
        $response = $factory->create($this->getFullParams());

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


    public function testErrorResponse(): void
    {
        $factory = TestHelpers::createResponseFactory();
        $response = $factory->create($this->getErrorParams());

        self::assertNull($response->getMd());
        self::assertSame(TestHelpers::ORDER_NUMBER, $response->getOrderNumber());
        self::assertNull($response->getMerOrderNumber());
        self::assertSame(14, $response->getPrcode());
        self::assertSame(0, $response->getSrcode());
        self::assertSame('Duplicate order number', $response->getResultText());
        self::assertSame('czk', $response->getGatewayKey());
        self::assertTrue($response->hasError());
    }

    /**
     * @return array<string, string>
     */
    private function getFullParams(): array
    {
        return [
            Param::OPERATION => 'CREATE_ORDER',
            Param::ORDERNUMBER => TestHelpers::ORDER_NUMBER,
            Param::MERORDERNUM => TestHelpers::MER_ORDER_NUM,
            Param::MD => 'czk|sometext',
            ResponseInterface::PRCODE => TestHelpers::PRCODE,
            ResponseInterface::SRCODE => TestHelpers::SRCODE,
            ResponseInterface::RESULTTEXT => TestHelpers::RESULTTEXT,
            Param::DIGEST => TestHelpers::HASH_1,
            ResponseInterface::DIGEST1 => TestHelpers::HASH_2,
            Param::TOKEN => 'XXXX',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function getErrorParams(): array
    {
        return [
            Param::OPERATION => 'CREATE_ORDER',
            Param::ORDERNUMBER => TestHelpers::ORDER_NUMBER,
            Param::MD => 'czk',
            ResponseInterface::PRCODE => '14',
            ResponseInterface::SRCODE => '0',
            ResponseInterface::RESULTTEXT => 'Duplicate order number',
            Param::DIGEST => TestHelpers::HASH_1,
            ResponseInterface::DIGEST1 => TestHelpers::HASH_2,
        ];
    }
}
