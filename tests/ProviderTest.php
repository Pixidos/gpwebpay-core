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

namespace Pixidos\GPWebPay\Tests;

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Provider;
use Pixidos\GPWebPay\Signer\ISigner;
use Pixidos\GPWebPay\Signer\ISignerFactory;
use UnexpectedValueException;

/**
 * Class ProviderTest
 * @package Pixidos\GPWebPay\Tests
 * @author  Ondra Votava <ondra@votava.dev>
 */
class ProviderTest extends TestCase
{

    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws SignerException
     * @throws UnexpectedValueException
     */
    public function testCreateRequest(): void
    {
        $provider = $this->createProvider();
        $operation = TestHelpers::createOperation();
        /** @var Request $request */
        $request = $provider->createRequest($operation);

        $params = $request->getParams();

        self::assertSame('203', $params['CURRENCY']);
        self::assertSame('123456', $params['ORDERNUMBER']);
    }


    /**
     * @throws Exception
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @throws SignerException
     * @throws UnexpectedValueException
     */
    public function testRequestUrl(): void
    {
        $provider = $this->createProvider();
        $operation = TestHelpers::createOperation();
        $request = $provider->createRequest($operation);

        $expected = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789'
            . '&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=100000&CURRENCY=203&DEPOSITFLAG=1&'
            . 'URL=http%3A%2F%2Ftest.com&MD=czk&DIGEST=kMl9tg%2Fup2z9CJu%2BEbgm7mg3XSGOAvY2ZkrtgqOtzSprh1L22bvshGRlfDT9'
            . '134Z2Hj1PWNitDOvgoAFnxyax8oIyx6eB4hMnNkB6xyr3X5XQXqsCsVRGYHYUOLvNuAag1kaNcVx%2Bjuqijxd0huvk60PMn5JjQijNl'
            . '4ij36YwoqyN4UdP16LjIqYRIngaeHsTTR1XgIVmJIcuIfETV1QsiQCOYPw0s%2FZTeri1DzpQq1Es5cERSupFBVp5Y8tJUna0Yx%2FoLh'
            . '2SBhsw6BPixm6jhLAjqvQn%2BgmMv4AKDfTYdSPDqg1A%2BT3XFK%2F%2BvE%2BzOGW0%2FDHKr2ZqNYUQyD1adi3QA%3D%3D';
        self::assertSame($expected, $provider->getRequestUrl($request));
    }

    /**
     * @return Provider
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws Exception
     * @throws RuntimeException
     */
    private function createProvider(): Provider
    {
        $signer = $this->createMock(ISigner::class);
        $signer
            ->expects(self::once())
            ->method('sign')
            ->willReturn(
                'kMl9tg/up2z9CJu+Ebgm7mg3XSGOAvY2ZkrtgqOtzSprh1L22bvshGRlfDT9134Z2Hj1PWNitDOvgoAFnxyax8oIyx6eB4hMnNkB6xyr3X5XQXqsCsVRGYHYUOLvNuA'
                . 'ag1kaNcVx+juqijxd0huvk60PMn5JjQijNl4ij36YwoqyN4UdP16LjIqYRIngaeHsTTR1XgIVmJIcuIfETV1QsiQCOYPw0s/ZTeri1DzpQq1Es5cERSupFBVp5Y8tJUna0Y'
                . 'x/oLh2SBhsw6BPixm6jhLAjqvQn+gmMv4AKDfTYdSPDqg1A+T3XFK/+vE+zOGW0/DHKr2ZqNYUQyD1adi3QA=='
            );
        $signerFactory = $this->createMock(ISignerFactory::class);
        $signerFactory->expects(self::once())->method('create')->willReturn($signer);

        $provider = new Provider(
            TestHelpers::createSettings(),
            $signerFactory,
            new RequestFactory(TestHelpers::createSettings(), $signerFactory),
            new ResponseFactory(TestHelpers::createSettings())
        );

        return $provider;
    }


}
