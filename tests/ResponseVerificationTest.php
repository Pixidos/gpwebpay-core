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

namespace Pixidos\GPWebPay\Tests;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\Config;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

/**
 * Responses signed the way GP webpay signs them must verify through
 * ResponseFactory -> ResponseProvider, and any change to a signed field must not.
 */
class ResponseVerificationTest extends TestCase
{
    private const ADDINFO = '<?xml version="1.0" encoding="UTF-8"?><additionalInfoResponse version="1.0">'
        . '<walletDetails>MPS</walletDetails></additionalInfoResponse>';

    private Config $config;
    private SignerProvider $signers;
    private ResponseFactory $factory;
    private ResponseProvider $provider;

    protected function setUp(): void
    {
        $this->config = TestHelpers::createMultiGatewayConfig();
        $this->signers = new SignerProvider(new SignerFactory(), $this->config->getSignerConfigProvider());
        $this->factory = new ResponseFactory($this->config->getPaymentConfigProvider());
        $this->provider = new ResponseProvider($this->config->getPaymentConfigProvider(), $this->signers);
    }

    /**
     * @dataProvider optionalSignedFields
     */
    public function testOptionalSignedFieldIsKeptAndVerified(string $name, string $value): void
    {
        $fields = $this->baseFields();
        $fields[$name] = $value;

        $response = $this->factory->create($this->signCzk($fields));

        self::assertArrayHasKey($name, $response->getParams());
        self::assertSame($value, (string)$response->getParams()[$name]);
        self::assertTrue($this->provider->verifyPaymentResponse($response));
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function optionalSignedFields(): array
    {
        return [
            'MERORDERNUM' => ['MERORDERNUM', '12345678'],
            'RESULTTEXT' => ['RESULTTEXT', 'OK'],
            'USERPARAM1 (before API 1.17)' => ['USERPARAM1', 'card-hash'],
            'ADDINFO' => ['ADDINFO', self::ADDINFO],
            'TOKEN' => ['TOKEN', 'token-0123456789'],
            'EXPIRY' => ['EXPIRY', '2912'],
            'ACSRES' => ['ACSRES', 'F'],
            'ACCODE' => ['ACCODE', '123456'],
            'PANPATTERN' => ['PANPATTERN', '405607******0016'],
            'DAYTOCAPTURE' => ['DAYTOCAPTURE', '31122026'],
            'TOKENREGSTATUS' => ['TOKENREGSTATUS', 'SUCCESS'],
            'ACRC' => ['ACRC', '00'],
            'RRN' => ['RRN', '123456789012'],
            'PAR' => ['PAR', 'V0010000000000000000000000000'],
            'TRACEID' => ['TRACEID', '123456789012345'],
        ];
    }

    public function testCompleteResponseWithAllCurrentFields(): void
    {
        $fields = $this->completeFields();
        $received = $this->signCzk($fields);

        $response = $this->factory->create($received);

        self::assertSame(array_keys($fields), array_keys($response->getParams()));
        self::assertTrue($this->provider->verifyPaymentResponse($response));

        $paid = null;
        $this->provider->addOnSuccess(static function (ResponseInterface $response) use (&$paid): void {
            $paid = $response->getOrderNumber();
        });
        $this->provider->provide($response);

        self::assertSame('123456', $paid);
        self::assertSame('order-7', $response->getMd());
        self::assertSame('czk', $response->getGatewayKey());
        self::assertNull($response->getUserParam1());
    }

    public function testLegacyResponseWithUserParam1(): void
    {
        $fields = $this->baseFields();
        $fields['RESULTTEXT'] = 'OK';
        $fields['USERPARAM1'] = 'card-hash';
        $fields['TOKEN'] = 'token';

        $response = $this->factory->create($this->signCzk($fields));

        self::assertSame('card-hash', $response->getUserParam1());
        self::assertTrue($this->provider->verifyPaymentResponse($response));
    }

    public function testOrderOfReceivedFieldsDoesNotMatter(): void
    {
        $received = array_reverse($this->signCzk($this->completeFields()), true);

        self::assertTrue($this->provider->verifyPaymentResponse($this->factory->create($received)));
    }

    public function testFieldsThatAreNotPartOfResponseAreIgnored(): void
    {
        $received = $this->signCzk($this->completeFields());
        $received += [
            'AMOUNT' => '1',
            'MERCHANTNUMBER' => '987654321',
            'LANG' => 'cs',
            'USERPARAM' => 'constant name, not a field',
            'FAST_TOKEN' => 'constant name, not a field',
            'unknown' => 'value',
            'array' => ['not', 'a', 'field'],
        ];

        $response = $this->factory->create($received);

        self::assertSame(array_keys($this->completeFields()), array_keys($response->getParams()));
        self::assertTrue($this->provider->verifyPaymentResponse($response));
    }

    public function testDigestsAreNotSignedFields(): void
    {
        $received = $this->signCzk($this->baseFields());
        $response = $this->factory->create($received);

        self::assertArrayNotHasKey('DIGEST', $response->getParams());
        self::assertArrayNotHasKey('DIGEST1', $response->getParams());
        self::assertSame($received['DIGEST'], $response->getDigest());
        self::assertSame($received['DIGEST1'], $response->getDigest1());
    }

    public function testValuesAreVerifiedExactlyAsReceived(): void
    {
        $fields = $this->baseFields();
        $fields['RESULTTEXT'] = ' OK ';
        $fields['ADDINFO'] = self::ADDINFO . "\n";

        $response = $this->factory->create($this->signCzk($fields));

        self::assertSame(' OK ', $response->getResultText());
        self::assertTrue($this->provider->verifyPaymentResponse($response));
    }

    /**
     * @dataProvider signedFieldNames
     */
    public function testChangedSignedFieldIsRejected(string $name): void
    {
        $received = $this->signCzk($this->completeFields());
        $received[$name] = match ($name) {
            'OPERATION' => 'CARD_VERIFICATION',
            'PRCODE', 'SRCODE' => '1',
            default => $received[$name] . '1',
        };

        $this->assertRejected($this->factory->create($received));
    }

    /**
     * @dataProvider optionalSignedFields
     */
    public function testRemovedSignedFieldIsRejected(string $name): void
    {
        $fields = $this->completeFields();
        $fields[$name] = $fields[$name] ?? 'value';
        $received = $this->signCzk($fields);
        unset($received[$name]);

        $this->assertRejected($this->factory->create($received));
    }

    /**
     * @return array<string, array{string}>
     */
    public static function signedFieldNames(): array
    {
        $names = [];
        foreach (TestHelpers::SPEC_RESPONSE_ORDER as $name) {
            if ('USERPARAM1' !== $name) {
                $names[$name] = [$name];
            }
        }

        return $names;
    }

    public function testDigest1IsBoundToTheMerchantNumberOfTheGateway(): void
    {
        $received = TestHelpers::signAsGateway(
            $this->signers->get('czk'),
            $this->baseFields(),
            TestHelpers::EUR_MERCHANT_NUMBER
        );

        $this->assertRejected($this->factory->create($received));
    }

    public function testSuccessCallbacksAreNotCalledForForgedResponse(): void
    {
        $fields = $this->completeFields();
        $fields['PRCODE'] = '30';
        $fields['SRCODE'] = '1002';
        $received = $this->signCzk($fields);
        $received['PRCODE'] = '0';
        $received['SRCODE'] = '0';

        $successCalled = false;
        $error = null;
        $this->provider->addOnSuccess(static function () use (&$successCalled): void {
            $successCalled = true;
        });
        $this->provider->addOnError(static function (GPWebPayException $exception) use (&$error): void {
            $error = $exception;
        });

        $this->provider->provide($this->factory->create($received));

        self::assertFalse($successCalled);
        self::assertInstanceOf(SignerException::class, $error);
    }

    private function assertRejected(ResponseInterface $response): void
    {
        self::assertFalse($this->provider->verifyPaymentResponse($response));
    }

    /**
     * @return array<string, string>
     */
    private function baseFields(): array
    {
        return [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => '123456',
            'MD' => 'czk',
            'PRCODE' => '0',
            'SRCODE' => '0',
        ];
    }

    /**
     * Every field of a current (API 1.19) card payment response, in specification order.
     *
     * @return array<string, string>
     */
    private function completeFields(): array
    {
        return [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => '123456',
            'MERORDERNUM' => '12345678',
            'MD' => 'czk|order-7',
            'PRCODE' => '0',
            'SRCODE' => '0',
            'RESULTTEXT' => 'OK',
            'ADDINFO' => self::ADDINFO,
            'TOKEN' => 'token-0123456789',
            'EXPIRY' => '2912',
            'ACSRES' => 'F',
            'ACCODE' => '123456',
            'PANPATTERN' => '405607******0016',
            'DAYTOCAPTURE' => '31122026',
            'TOKENREGSTATUS' => 'SUCCESS',
            'ACRC' => '00',
            'RRN' => '123456789012',
            'PAR' => 'V0010000000000000000000000000',
            'TRACEID' => '123456789012345',
        ];
    }

    /**
     * @param array<string, string> $fields
     * @return array<string, string>
     */
    private function signCzk(array $fields): array
    {
        return TestHelpers::signAsGateway($this->signers->get('czk'), $fields, TestHelpers::CZK_MERCHANT_NUMBER);
    }
}
