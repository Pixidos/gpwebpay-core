<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Tests;

use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;
use Pixidos\GPWebPay\Settings\Settings;
use Pixidos\GPWebPay\Settings\SettingsFactory;
use UnexpectedValueException;

/**
 * Class TestHelpers
 * @package PixidosTests\GPWebPay
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
class TestHelpers
{
    public const ORDER_NUMBER = '123456';
    public const MER_ORDER_NUM = 'FA12345';
    public const PRCODE = '0';
    public const SRCODE = '0';
    public const RESULTTEXT = 'resulttext';
    public const HASH_1 = 'hash1';
    public const HASH_2 = 'hash2';
    public const GATEWAY = 'czk';
    public const RESPONSE_URL = 'http://test.com';
    /**
     * @var string|null
     */
    private static $longText300;
    private static $longText3000;
    /**
     * @var string
     */
    private static $longText30000;

    /**
     * @return Operation
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public static function createOperation(): Operation
    {
        return new Operation(
            new OrderNumber(self::ORDER_NUMBER),
            new Amount(1000),
            new Currency(\Pixidos\GPWebPay\Enum\Currency::CZK()),
            self::GATEWAY,
            new ResponseUrl(self::RESPONSE_URL)
        );
    }

    public static function getTestParams(): array
    {
        return [
            Param::OPERATION => 'CREATE_ORDER',
            Param::ORDERNUMBER => self::ORDER_NUMBER,
            Param::MERORDERNUM => self::MER_ORDER_NUM,
            Param::MD => 'czk|sometext',
            IResponse::PRCODE => self::PRCODE,
            IResponse::SRCODE => self::SRCODE,
            IResponse::RESULTTEXT => self::RESULTTEXT,
            Param::DIGEST => self::HASH_1,
            IResponse::DIGEST_1 => self::HASH_2,
            'gatewayKey' => self::GATEWAY,
        ];
    }


    /**
     * @return Settings
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public static function createSettings(): Settings
    {
        return SettingsFactory::create(
            __DIR__ . '/_certs/test.pem',
            '1234567',
            __DIR__ . '/_certs/test-pub.pem',
            'https://test.3dsecure.gpwebpay.com/unicredit/order.do',
            '123456789'
        );
    }

    public static function getLongText300(): string
    {
        if (self::$longText300 === null) {
            self::$longText300 = trim((string)file_get_contents(__DIR__ . '/_data/_300_long_string.txt'), "\n");
        }

        return self::$longText300;
    }

    public static function getLongText3000(): string
    {
        if (self::$longText3000 === null) {
            self::$longText3000 = trim((string)file_get_contents(__DIR__ . '/_data/_3000_long_string.txt'), "\n");
        }

        return self::$longText3000;
    }

    public static function getLongText30000(): string
    {
        if (self::$longText30000 === null) {
            self::$longText30000 = trim((string)file_get_contents(__DIR__ . '/_data/_30000_long_string.txt'), "\n");
        }

        return self::$longText30000;
    }
}
