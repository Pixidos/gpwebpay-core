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
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Provider;
use Pixidos\GPWebPay\Signer\SignerFactory;
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
        //phpcs:disable
        $expected = 'https://test.3dsecure.gpwebpay.com/unicredit/order.do?MERCHANTNUMBER=123456789&OPERATION=CREATE_ORDER&ORDERNUMBER=123456&AMOUNT=100000&CURRENCY=203&DEPOSITFLAG=1&URL=http%3A%2F%2Ftest.com&MD=czk&DIGEST=F0F%2Bb%2FyUUGmyzs7rOMXKD06JJ8EJrdit2YT2JaotVM3BaPe2adSk2MR1pmEWBLstKTZu2W4QIdYIgV8W7sKQ8wA96fmzJaCXzk%2BUEGdy2cRGG7u0ghsmuEu%2FR%2FR%2BprjujZx7YoVSPn4g%2FXQ9yVK1Svz23SYKnTOwiBGHd1sb2EHAjoO02o22FlHRP8Z%2F41oABNZt%2BycM7xWX%2Fx3YL01zGY99Mf2ulfe2UYaZ2nJtPa3FHuMPNJGfLPSFvTiIeEGCJ2%2BIkBqc5oTX0MjM8q2BojwSb%2BW%2Fev7N4fusQM%2BV2UjNZrXeMHfJGJkDE3VwNAY0AKaK%2Bcu6NDxHYsNswjyaWg%3D%3D';
        //phpcs:enable
        self::assertSame($expected, $provider->getRequestUrl($request));
    }

    public function testVerifyResponse(): void
    {
        $response = $this->createResponse();
        $provider = $this->createProvider();

        self::assertTrue($provider->verifyPaymentResponse($response));
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
        $settings = TestHelpers::createSettings();
        $signerFactory = new SignerFactory($settings);

        return new Provider(
            $settings,
            $signerFactory,
            new RequestFactory($settings, $signerFactory),
            new ResponseFactory($settings)
        );
    }

    private function createResponse(): IResponse
    {
        $factory = new ResponseFactory(TestHelpers::createSettings());

        return $factory->create($this->getParams());
    }

    /**
     * @return array
     */
    private function getParams(): array
    {
        // phpcs:disable
        return [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => 123456789,
            'MD' => 'czk',
            'PRCODE' => 0,
            'SRCODE' => 0,
            'RESULTTEXT' => 'OK',
            'DIGEST' => 'ttraG4dWR7mW49WYmdKqob63jh4OGhJ+rXv1urImUB/aFqle5v619xlGHhvhBUpRzNfQsvR1FwYvgymItxcgwfE1ZucDfuiI+pslCJVMipRpS93N3gDSdaMqhogZeQLtqdYy/zm+2vyDzQrdqPMdTK+4t09z4O09joQOPWHoMZiFHl5fpiTGKHm2o5wTMbUoiBzGqHw4ZFw9mdvpmDs2xGV3zuFow/2uU+n6Ld54ghbt2XZcUwzwUtzCn3AIUWp1ArkGIPEjaXErD6QrpJI4tnSVPjepU3Iwh4YY87QClrev/Rbkrix5oCTjwCvOKXObEmy1v9QypFvtBtmff0nOww==',
            'DIGEST1' => 'Fw+jhpY9N5pEqHW9huA9GLP/1p07NnbuPIQDDJ1HdvO8xXBjD355o1LqiF1gAFE42fLi4kmi0mlyl30g0R/xv8Z2JeBf+X/sSGeSKrGGwyDnCCIwDCfjJnfXo+qXJTfv89fhrkehOkpwFiYpkqTeAs36xnELZQCaPzxOG8DzTH1DPuIt4fUlqqE01HLE3ssl0Aof/lRTgLuEMg24gYhgb1+qSixjnYbwD6nRZKAQw3PkT43yAYvxmKpIyo1/632tNQyQrs1p+DFqMYEIxxqJIA48texWVEp6hkFNzA4hvytWnCssaOiLGJc3rH3Gew5VNh++PxWXJLNi/l2K/MFTlA==',
        ];
        //phpcs:enable
    }

}
