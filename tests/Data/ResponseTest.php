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

/**
 * Test: Pixidos\GPWebPay\Data\Response
 * @testCase PixidosTests\GPWebPay\ResponseTest
 */

namespace Pixidos\GPWebPay\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\Token;
use Pixidos\GPWebPay\Param\UserParam;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class ResponseTest
 * @package PixidosTests\GPWebPay
 * @author  Ondra Votava <ondra@votava.dev>
 */
class ResponseTest extends TestCase
{
    public function testCreateGPWebPayResponse(): void
    {
        $params = TestHelpers::getTestParams();

        $response = new Response(
            $params[Param::OPERATION],
            $params[Param::ORDERNUMBER],
            $params[Param::MERORDERNUM],
            $params[Param::MD],
            (int)$params[ResponseInterface::PRCODE],
            (int)$params[ResponseInterface::SRCODE],
            $params[ResponseInterface::RESULTTEXT],
            $params[Param::DIGEST],
            $params[ResponseInterface::DIGEST1],
            $params['gatewayKey']
        );

        $response->addParam(new UserParam('userparam'));

        self::assertSame('123456', $response->getOrderNumber());
        self::assertSame('12345678', $response->getMerOrderNumber());
        self::assertSame('sometext', $response->getMd());
        self::assertSame(0, $response->getPrcode());
        self::assertSame(0, $response->getSrcode());
        self::assertSame('resulttext', $response->getResultText());
        self::assertSame('hash1', $response->getDigest());
        self::assertSame('hash2', $response->getDigest1());
        self::assertSame('czk', $response->getGatewayKey());
        self::assertSame('userparam', $response->getUserParam1());
        self::assertFalse($response->hasError());
    }


    public function testResponseParams(): void
    {
        $params = TestHelpers::getTestParams();
        $response = new Response(
            $params[Param::OPERATION],
            $params[Param::ORDERNUMBER],
            $params[Param::MERORDERNUM],
            $params[Param::MD],
            (int)$params[ResponseInterface::PRCODE],
            (int)$params[ResponseInterface::SRCODE],
            $params[ResponseInterface::RESULTTEXT],
            $params[Param::DIGEST],
            $params[ResponseInterface::DIGEST1],
            $params['gatewayKey']
        );
        $response->addParam(new Token('XXXX'));

        self::assertSame('sometext', $response->getMd());
        self::assertSame(TestHelpers::ORDER_NUMBER, $response->getOrderNumber());
        self::assertSame(TestHelpers::MER_ORDER_NUM, $response->getMerOrderNumber());
        self::assertSame(0, $response->getPrcode());
        self::assertSame(0, $response->getSrcode());
        self::assertSame(TestHelpers::RESULTTEXT, $response->getResultText());
        self::assertSame('czk', $response->getGatewayKey());
        self::assertSame('XXXX', $response->getParams()[Param::TOKEN]->getValue());
    }


    public function testError(): void
    {
        $params = TestHelpers::getTestParams();

        $response = new Response(
            $params[Param::OPERATION],
            $params[Param::ORDERNUMBER],
            $params[Param::MERORDERNUM],
            $params[Param::MD],
            1000,
            30,
            $params[ResponseInterface::RESULTTEXT],
            $params[Param::DIGEST],
            $params[ResponseInterface::DIGEST1],
            $params['gatewayKey']
        );

        self::assertTrue($response->hasError());
    }
}
