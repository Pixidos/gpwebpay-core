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
use Pixidos\GPWebPay\Config\SignerConfigProvider;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\InvalidResponseException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;
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
     * @dataProvider receivedMd
     */
    public function testMdSelectsGatewayAndKeepsSignedValue(string $md, string $gateway, ?string $payload): void
    {
        $config = TestHelpers::createMultiGatewayConfig();
        $signers = new SignerProvider(new SignerFactory(), $config->getSignerConfigProvider());
        $provider = new ResponseProvider($config->getPaymentConfigProvider(), $signers);
        $merchantNumber = 'eur' === $gateway ? TestHelpers::EUR_MERCHANT_NUMBER : TestHelpers::CZK_MERCHANT_NUMBER;
        $received = TestHelpers::signAsGateway(
            $signers->get($gateway),
            [
                Param::OPERATION => 'CREATE_ORDER',
                Param::ORDERNUMBER => '123456',
                Param::MD => $md,
                ResponseInterface::PRCODE => '0',
                ResponseInterface::SRCODE => '0',
            ],
            $merchantNumber
        );

        /** @var Response $response */
        $response = (new ResponseFactory($config->getPaymentConfigProvider()))->create($received);

        self::assertSame($gateway, $response->getGatewayKey());
        self::assertSame($payload, $response->getMd());
        self::assertSame($md, $response->getRawMd());
        self::assertSame($md, (string)$response->getParams()[Param::MD]);
        self::assertTrue($provider->verifyPaymentResponse($response));
    }

    /**
     * @return array<string, array{string, string, string|null}>
     */
    public static function receivedMd(): array
    {
        $longPayload = str_repeat('x', 251);

        return [
            'gateway only' => ['eur', 'eur', null],
            'gateway with Integration Advice Switch ID' => ['czk#ID=200323-S1APST31-000001299560', 'czk', null],
            'gateway with PayPal ID' => ['eur#IDPP=2RN85480PL048943C', 'eur', null],
            'gateway and merchant data' => ['eur|order-7', 'eur', 'order-7'],
            'merchant data with PayPal ID' => ['eur|order-7#IDPP=2RN85480PL048943C', 'eur', 'order-7'],
            'merchant data with both IDs' => ['czk|order-7#ID=200323-S1APST31-000001299560#IDPP=2RN8', 'czk', 'order-7'],
            'merchant data equal to gateway' => ['eur|eur', 'eur', 'eur'],
            'merchant data with separator' => ['eur|a|b', 'eur', 'a|b'],
            'empty merchant data' => ['eur|', 'eur', ''],
            'default gateway with merchant data' => ['|order-7', 'czk', 'order-7'],
            'default gateway, merchant data and ID' => ['|order-7#ID=200323-S1APST31-000001299560', 'czk', 'order-7'],
            'only ID appended by GP webpay' => ['#ID=200323-S1APST31-000001299560', 'czk', null],
            'upper-case gateway' => ['EUR|order-7', 'eur', 'order-7'],
            '255 characters and PayPal ID' => ['czk|' . $longPayload . '#IDPP=2RN85480PL048943C', 'czk', $longPayload],
        ];
    }

    /**
     * @dataProvider unknownGatewayMd
     */
    public function testMdWithUnknownGatewayIsRejected(string $md): void
    {
        $this->expectException(InvalidResponseException::class);
        $this->expectExceptionMessage('MD selects a gateway that is not configured.');

        $params = $this->getErrorParams();
        $params[Param::MD] = $md;
        TestHelpers::createResponseFactory()->create($params);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function unknownGatewayMd(): array
    {
        return [
            'gateway only' => ['xyz'],
            'gateway and merchant data' => ['xyz|order-7'],
            'gateway with ID' => ['xyz#ID=200323-S1APST31-000001299560'],
        ];
    }

    /**
     * @dataProvider malformedResponses
     *
     * @param array<string, mixed> $params
     */
    public function testMalformedResponseIsRejected(array $params, string $message): void
    {
        try {
            TestHelpers::createResponseFactory()->create($params);
            self::fail('InvalidResponseException expected');
        } catch (GPWebPayException $exception) {
            // applications can handle every rejected response by catching GPWebPayException
            self::assertInstanceOf(InvalidResponseException::class, $exception);
            self::assertStringContainsString($message, $exception->getMessage());
        }
    }

    /**
     * @return array<string, array{array<string, mixed>, string}>
     */
    public static function malformedResponses(): array
    {
        $valid = [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => '123456',
            'MD' => 'czk',
            'PRCODE' => '0',
            'SRCODE' => '0',
            'DIGEST' => 'digest',
            'DIGEST1' => 'digest1',
        ];
        $without = static function (string $name) use ($valid): array {
            unset($valid[$name]);

            return $valid;
        };

        return [
            'no data' => [[], 'OPERATION is missing'],
            'missing OPERATION' => [$without('OPERATION'), 'OPERATION is missing'],
            'unknown OPERATION' => [['OPERATION' => 'REFUND'] + $valid, 'Invalid GP webpay response'],
            'missing ORDERNUMBER' => [$without('ORDERNUMBER'), 'ORDERNUMBER is missing'],
            'ORDERNUMBER not a number' => [['ORDERNUMBER' => 'abc'] + $valid, 'ORDERNUMBER must be'],
            'MERORDERNUM not a number' => [['MERORDERNUM' => 'abc'] + $valid, 'MERORDERNUM must be'],
            'empty OPERATION' => [['OPERATION' => ''] + $valid, 'Invalid GP webpay response'],
            'empty ORDERNUMBER' => [['ORDERNUMBER' => ''] + $valid, 'ORDERNUMBER must be'],
            'empty PRCODE' => [['PRCODE' => ''] + $valid, 'PRCODE must be a non-negative integer'],
            'empty SRCODE' => [['SRCODE' => ''] + $valid, 'SRCODE must be a non-negative integer'],
            'missing PRCODE' => [$without('PRCODE'), 'PRCODE is missing'],
            'PRCODE not a number' => [['PRCODE' => 'abc'] + $valid, 'PRCODE must be a non-negative integer'],
            'PRCODE with leading zero' => [['PRCODE' => '00'] + $valid, 'PRCODE must be a non-negative integer'],
            'SRCODE negative' => [['SRCODE' => '-1'] + $valid, 'SRCODE must be a non-negative integer'],
            'array OPERATION' => [['OPERATION' => ['CREATE_ORDER']] + $valid, 'OPERATION must be a string, array given'],
            'array MD' => [['MD' => ['czk']] + $valid, 'MD must be a string, array given'],
            'array TOKEN' => [['TOKEN' => ['x']] + $valid, 'TOKEN must be a string, array given'],
            'array DIGEST' => [['DIGEST' => ['x']] + $valid, 'DIGEST must be a string, array given'],
        ];
    }

    public function testConfigurationErrorsAreNotTurnedIntoResponseErrors(): void
    {
        $config = TestHelpers::createMultiGatewayConfig();
        $signerConfig = new SignerConfigProvider();
        $signerConfig->addConfig($config->getSignerConfigProvider()->getConfig('czk'), 'czk');
        $signerConfig->setDefaultGateway('czk');
        $provider = new ResponseProvider(
            $config->getPaymentConfigProvider(),
            new SignerProvider(new SignerFactory(), $signerConfig)
        );
        $provider->addOnError(static function (): void {
            self::fail('A missing signer configuration is not a payment error.');
        });
        $params = $this->getErrorParams();
        $params[Param::MD] = 'eur';
        $response = (new ResponseFactory($config->getPaymentConfigProvider()))->create($params);

        $this->expectException(InvalidArgumentException::class);
        $provider->provide($response);
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
