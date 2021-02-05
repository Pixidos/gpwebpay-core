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

use Pixidos\GPWebPay\Config\Config;
use Pixidos\GPWebPay\Config\Factory\ConfigFactory;
use Pixidos\GPWebPay\Config\Factory\PaymentConfigFactory;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;
use UnexpectedValueException;

/**
 * Class TestHelpers
 * @package PixidosTests\GPWebPay
 * @author  Ondra Votava <ondra.votava@pixidos.com>
 */
class TestHelpers
{
    public const ORDER_NUMBER = '123456';
    public const MER_ORDER_NUM = '12345678';
    public const PRCODE = '0';
    public const SRCODE = '0';
    public const RESULTTEXT = 'resulttext';
    public const HASH_1 = 'hash1';
    public const HASH_2 = 'hash2';
    public const GATEWAY = 'czk';
    public const RESPONSE_URL = 'http://test.com';
    public const CZK = 'czk';
    public const EUR = 'eur';

    /**
     * @var string|null
     */
    private static $longText300 = null;
    /**
     * @var string|null
     */
    private static $longText3000 = null;
    /**
     * @var string
     */
    private static $longText30000 = null;
    /**
     * @var Config|null
     */
    private static $config = null;
    /**
     * @var Operation|null
     */
    private static $operation = null;
    /**
     * @var ResponseFactory|null
     */
    private static $responseFactory = null;

    /**
     * @return Operation
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public static function createOperation(): Operation
    {
        if (null !== self::$operation) {
            return self::$operation;
        }

        return self::$operation = new Operation(
            new OrderNumber(self::ORDER_NUMBER),
            new Amount(1000),
            new Currency(\Pixidos\GPWebPay\Enum\Currency::CZK()),
            self::GATEWAY,
            new ResponseUrl(self::RESPONSE_URL)
        );
    }

    /**
     * @param array<string, string> $params
     * @return ResponseInterface
     */
    public static function createResponse(array $params): ResponseInterface
    {
        return self::createResponseFactory()->create($params);
    }

    /**
     * @return ResponseFactory
     */
    public static function createResponseFactory(): ResponseFactory
    {
        if (null !== self::$responseFactory) {
            return self::$responseFactory;
        }
        $config = self::createConfig();

        return self::$responseFactory = new ResponseFactory($config->getPaymentConfigProvider());
    }


    /**
     * @return array<string, string>
     */
    public static function getTestParams(): array
    {
        return [
            Param::OPERATION => 'CREATE_ORDER',
            Param::ORDERNUMBER => self::ORDER_NUMBER,
            Param::MERORDERNUM => self::MER_ORDER_NUM,
            Param::MD => 'czk|sometext',
            ResponseInterface::PRCODE => self::PRCODE,
            ResponseInterface::SRCODE => self::SRCODE,
            ResponseInterface::RESULTTEXT => self::RESULTTEXT,
            Param::DIGEST => self::HASH_1,
            ResponseInterface::DIGEST1 => self::HASH_2,
            'gatewayKey' => self::GATEWAY,
        ];
    }


    /**
     * @return Config
     */
    public static function createConfig(): Config
    {
        if (null !== self::$config) {
            return self::$config;
        }

        $factory = new ConfigFactory(new PaymentConfigFactory());

        return self::$config = $factory->create(
            [
                'czk' => [
                    ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test.pem',
                    ConfigFactory::PRIVATE_KEY_PASSPHRASE => '1234567',
                    ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub.pem',
                    ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                    ConfigFactory::MERCHANT_NUMBER => '123456789',
                    ConfigFactory::DEPOSIT_FLAG => 1,
                ],
                'eur' => [
                    ConfigFactory::PRIVATE_KEY => __DIR__ . '/_certs/test2.pem',
                    ConfigFactory::PRIVATE_KEY_PASSPHRASE => '12345678',
                    ConfigFactory::PUBLIC_KEY => __DIR__ . '/_certs/test-pub2.pem',
                    ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
                    ConfigFactory::MERCHANT_NUMBER => '123456780',
                    ConfigFactory::DEPOSIT_FLAG => 1,
                ],
            ],
            'czk'
        );
    }

    public static function getLongText300(): string
    {
        if (null === self::$longText300) {
            self::$longText300 = trim((string)file_get_contents(__DIR__ . '/_data/_300_long_string.txt'), "\n");
        }

        return self::$longText300;
    }

    public static function getLongText3000(): string
    {
        if (null === self::$longText3000) {
            self::$longText3000 = trim((string)file_get_contents(__DIR__ . '/_data/_3000_long_string.txt'), "\n");
        }

        return self::$longText3000;
    }

    public static function getLongText30000(): string
    {
        if (null === self::$longText30000) {
            self::$longText30000 = trim((string)file_get_contents(__DIR__ . '/_data/_30000_long_string.txt'), "\n");
        }

        return self::$longText30000;
    }
}
