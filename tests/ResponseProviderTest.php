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
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\ResponseProvider;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;
use UnexpectedValueException;

/**
 * Class ProviderTest
 * @package Pixidos\GPWebPay\Tests
 * @author  Ondra Votava <ondra@votava.dev>
 */
class ResponseProviderTest extends TestCase
{


    public function testSuccessVerifyResponse(): void
    {
        $provider = $this->createProvider();
        $response = TestHelpers::createResponse($this->getOkParams());

        self::assertTrue($provider->verifyPaymentResponse($response));
    }

    /**
     * @dataProvider getDigestParams
     *
     * @param string $key
     */
    public function testFailedVerifyResponse(string $key): void
    {
        $provider = $this->createProvider();
        $params = $this->getOkParams();
        $params[$key] = 'Bad hash';
        $response = TestHelpers::createResponse($params);

        self::assertFalse($provider->verifyPaymentResponse($response));
    }


    public function testErrorResponse(): void
    {
        $this->expectException(GPWebPayResultException::class);
        $this->expectExceptionMessage('Response has an error.');

        $provider = $this->createProvider();
        $response = TestHelpers::createResponse($this->getErrorParams());

        $provider->provide($response);
    }

    public function testOnError(): void
    {
        $provider = $this->createProvider();
        $response = TestHelpers::createResponse($this->getErrorParams());

        $called = false;
        $provider->addOnError(
            static function (GPWebPayException $exception, IResponse $response) use (&$called) {
                $called = true;
            }
        );
        $provider->provide($response);

        self::assertTrue($called);
    }

    public function testOnSuccess(): void
    {
        $provider = $this->createProvider();
        $response = TestHelpers::createResponse($this->getOkParams());

        $called = false;
        $provider->addOnSuccess(
            static function (IResponse $response) use (&$called) {
                $called = true;
            }
        );
        $provider->provide($response);

        self::assertTrue($called);
    }

    public function getDigestParams(): array
    {
        return [
            'digets' => ['DIGEST'],
            'digets 1' => ['DIGEST1']
        ];
    }


    /**
     * @return ResponseProvider
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     * @throws Exception
     * @throws RuntimeException
     */
    private function createProvider(): ResponseProvider
    {
        $config = TestHelpers::createConfig();
        $signerFactory = new SignerFactory();
        $configProvider = $config->getPaymentConfigProvider();
        $signerProvider = new SignerProvider($signerFactory, $config->getSignerConfigProvider());

        return new ResponseProvider(
            $configProvider,
            $signerProvider
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
