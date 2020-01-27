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
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
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

    public function testSuccessVerifyResponse(): void
    {
        $provider = $this->createProvider();
        $response = $provider->createResponse($this->getOkParams());

        self::assertTrue($provider->verifyPaymentResponse($response));
    }

    /**
     * @dataProvider getDigetsParams
     *
     * @param string $key
     */
    public function testFailedVerifyResponse(string $key): void
    {
        $this->expectException(GPWebPayException::class);
        $this->expectExceptionMessage('Digest or Digest1 is incorrect!');
        $params = $this->getOkParams();
        $params[$key] = 'Bad hash';
        $provider = $this->createProvider();
        $response = $provider->createResponse($params);
        $provider->verifyPaymentResponse($response);
    }


    public function testErrorResponse(): void
    {
        $this->expectException(GPWebPayResultException::class);
        $this->expectExceptionMessage('Response has an error.');

        $provider = $this->createProvider();
        $response = $provider->createResponse($this->getErrorParams());
        $provider->verifyPaymentResponse($response);
    }

    public function getDigetsParams(): array
    {
        return [
            'digets' => ['DIGEST'],
            'digets 1' => ['DIGEST1']
        ];
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

    /**
     * @return array
     */
    private function getOkParams(): array
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

    /**
     * @return array
     */
    private function getErrorParams(): array
    {
        // phpcs:disable
        return [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => 123456789,
            'MD' => 'czk',
            'PRCODE' => 14,
            'SRCODE' => 0,
            'RESULTTEXT' => 'Duplicate order number',
            'DIGEST' => 'dC9qPY4tCKa1kW+Z6x+qcaQY3ysxw5yQ/cMXx3ZjPX+movIwc1U7ZbAeOO/UwHQXO1WP0ZnFz+ofIWDdru+iCFNU9psTO9CoODjjJ6/USjLrvaLekUfNgHAYGj8x95PROFm7FqrXzMciWK8e46TMF/sh87VvxwI5PjIlrnIyBiu7j4E1a2UgIQgc8/s1CILvPbja/EMMs8lbI0EUQPdcl7A00GBk0tbUIvd71k1SGWhRmRIXzL5ECUBq6Bia7DVcSaF9qWOhGT3YdAFumjQVRTqZz6k3qZ68DYL2dlK3Iz1Zsaxri2z+SaB+D8+6ZnWFIecwod5D32lC1odW7Dp4zw==',
            'DIGEST1' => 'RC42A0v24Ec+218B1ZTmyVl1hMiYrJrYrYGuslmSe1ye/3jMhOVZE3TmtCwv43/KqB9MP36l9lXzdFym0nnD66W+PGgeoPsUYslhN3x3igu49vNWJzi010WddQMzTMUgYz1aiO/y81il3IAV+35b/WhnlxOPd0is0Xc/5uHTkle2NZVX6TrQLa0F6MlkI0h6uJi2Gowlr6k+LEyBDJvcajh1d/IQ8q5FiLictOz6rrhKgKcWiW4xfKNnbYB20U8/WUhkLrW7vLPqOietf2PUJurcWzq5GbN72Rqi8Bp/9HYNQJXnxnlUiUEc4K6qDAzQgnXeC8Fv3BTux9b8Y/3tJQ==',
        ];
        //phpcs:enable
    }

}
