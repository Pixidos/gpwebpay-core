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

use PHPUnit\Framework\Exception;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\Config;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\LogicException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\AmountInPennies;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\Email;
use Pixidos\GPWebPay\Param\Lang;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\PanPattern;
use Pixidos\GPWebPay\Param\ResponseUrl;
use Pixidos\GPWebPay\Param\Token;
use Pixidos\GPWebPay\Param\VRCode;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;
use Pixidos\GPWebPay\Tests\TestHelpers;
use UnexpectedValueException;

class RequestFactoryTest extends TestCase
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
        $factory = $this->createFactory();
        $operation = TestHelpers::createOperation();
        /** @var Request $request */
        $request = $factory->create($operation);

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
        $factory = $this->createFactory();
        $request = $factory->create(TestHelpers::createOperation());
        //phpcs:disable
        $expected = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=100000&CURRENCY=203&DEPOSITFLAG=1&URL=http%3A%2F%2Ftest.com&MD=czk&DIGEST=F0F%2Bb%2FyUUGmyzs7rOMXKD06JJ8EJrdit2YT2JaotVM3BaPe2adSk2MR1pmEWBLstKTZu2W4QIdYIgV8W7sKQ8wA96fmzJaCXzk%2BUEGdy2cRGG7u0ghsmuEu%2FR%2FR%2BprjujZx7YoVSPn4g%2FXQ9yVK1Svz23SYKnTOwiBGHd1sb2EHAjoO02o22FlHRP8Z%2F41oABNZt%2BycM7xWX%2Fx3YL01zGY99Mf2ulfe2UYaZ2nJtPa3FHuMPNJGfLPSFvTiIeEGCJ2%2BIkBqc5oTX0MjM8q2BojwSb%2BW%2Fev7N4fusQM%2BV2UjNZrXeMHfJGJkDE3VwNAY0AKaK%2Bcu6NDxHYsNswjyaWg%3D%3D';
        //phpcs:enable
        self::assertSame($expected, $request->getRequestUrl());
    }

    public function testForgotResponseUrlThrowException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You are forgot setup response url');

        $operation = new Operation(
            new OrderNumber('1234'),
            new AmountInPennies(100000),
            new Currency(\Pixidos\GPWebPay\Enum\Currency::CZK())
        );

        $factory = $this->createFactory();
        $factory->create($operation);
    }

    /**
     * @dataProvider gatewayResponseUrls
     */
    public function testResponseUrlOfTheSelectedGatewayIsUsed(?string $gateway, string $merchantNumber, string $url): void
    {
        $config = TestHelpers::createMultiGatewayConfig('https://shop.example/czk-return', 'https://shop.example/eur-return');
        $factory = $this->createFactoryFor($config);

        $params = $factory->create($this->createOperation($gateway))->getParams();

        self::assertSame($merchantNumber, $params[Param::MERCHANTNUMBER]);
        self::assertSame($url, $params[Param::RESPONSE_URL]);
    }

    /**
     * @return array<string, array{string|null, string, string}>
     */
    public static function gatewayResponseUrls(): array
    {
        return [
            'default gateway' => [null, TestHelpers::CZK_MERCHANT_NUMBER, 'https://shop.example/czk-return'],
            'czk' => ['czk', TestHelpers::CZK_MERCHANT_NUMBER, 'https://shop.example/czk-return'],
            'eur' => ['eur', TestHelpers::EUR_MERCHANT_NUMBER, 'https://shop.example/eur-return'],
        ];
    }

    public function testResponseUrlConfiguredOnlyForNonDefaultGateway(): void
    {
        $factory = $this->createFactoryFor(TestHelpers::createMultiGatewayConfig(null, 'https://shop.example/eur-return'));

        $params = $factory->create($this->createOperation('eur'))->getParams();
        self::assertSame('https://shop.example/eur-return', $params[Param::RESPONSE_URL]);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('You are forgot setup response url (gateway "czk")');
        $factory->create($this->createOperation('czk'));
    }

    public function testResponseUrlOfOperationHasPriority(): void
    {
        $factory = $this->createFactoryFor(TestHelpers::createMultiGatewayConfig(null, 'https://shop.example/eur-return'));
        $operation = $this->createOperation('eur');
        $operation->addParam(new ResponseUrl('https://shop.example/explicit'));

        self::assertSame('https://shop.example/explicit', $factory->create($operation)->getParams()[Param::RESPONSE_URL]);
    }

    public function testVrCodeAndPanPatternAreSignedInSpecificationOrder(): void
    {
        $config = TestHelpers::createMultiGatewayConfig('https://shop.example/return');
        $signers = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $factory = new RequestFactory($config->getPaymentConfigProvider(), $signers);
        $operation = $this->createOperation('czk');
        $operation->addParam(new Lang('cs'));
        $operation->addParam(new PanPattern('405607*0016'));
        $operation->addParam(new Token('token'));
        $operation->addParam(new VRCode('0011223344556677'));
        $operation->addParam(new Email('customer@example.com'));
        $operation->addParam(new Md('order-7'));

        $request = $factory->create($operation);
        $params = $request->getParams();
        $signed = $params;
        unset($signed[Param::DIGEST], $signed[Param::LANG]);

        // GP webpay HTTP API 1.19, pages 7-8
        $specificationOrder = [
            'MERCHANTNUMBER', 'OPERATION', 'ORDERNUMBER', 'AMOUNT', 'CURRENCY', 'DEPOSITFLAG', 'URL', 'MD',
            'VRCODE', 'EMAIL', 'PANPATTERN', 'TOKEN',
        ];
        self::assertSame($specificationOrder, array_keys($request->getDigestParams()));
        self::assertSame($signed, $request->getDigestParams());
        self::assertSame([...$specificationOrder, 'DIGEST', 'LANG'], array_keys($params));
        self::assertTrue($signers->get('czk')->verify($signed, $params[Param::DIGEST]));
    }

    private function createOperation(?string $gateway): Operation
    {
        return new Operation(
            new OrderNumber('1001'),
            new AmountInPennies(100),
            new Currency(\Pixidos\GPWebPay\Enum\Currency::CZK()),
            $gateway
        );
    }

    private function createFactoryFor(Config $config): RequestFactory
    {
        return new RequestFactory(
            $config->getPaymentConfigProvider(),
            new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider())
        );
    }

    public function createFactory(): RequestFactory
    {
        $config = TestHelpers::createConfig();
        $signerFactory = new SignerFactory();
        $signerProvider = new SignerProvider($signerFactory, $config->getSignerConfigProvider());

        return new RequestFactory($config->getPaymentConfigProvider(), $signerProvider);
    }
}
