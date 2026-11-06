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

namespace Pixidos\GPWebPay\Tests\Data;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Data\Operation;
use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\AmountInPennies;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;
use UnexpectedValueException;

/**
 * Class OperationTest
 * @package Pixidos\GPWebPay\Tests\Data
 */
class OperationTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function testSuccessCreateBasicOperation(): void
    {
        $operation = new Operation(
            new OrderNumber('123456'),
            new AmountInPennies(100000),
            new Currency(CurrencyEnum::CZK()),
            'CZK',
            new ResponseUrl('http://response.com/proccess-gpw-response')
        );

        $operation->addParam(new Md('someMd'));

        self::assertSame('123456', (string)$operation->getParam(Param::ORDERNUMBER()));
        self::assertSame('100000', (string)$operation->getParam(Param::AMOUNT()));
        self::assertSame('203', (string)$operation->getParam(Param::CURRENCY()));
        self::assertSame('czk', $operation->getGateway());
        $responseUrl = $operation->getParam(Param::RESPONSE_URL());
        self::assertNotNull($responseUrl);
        self::assertSame('http://response.com/proccess-gpw-response', $responseUrl->getValue());
        self::assertSame('czk|someMd', (string)$operation->getParam(Param::MD()));
    }

    public function testGatewayAloneIsOnlyRoutingMarker(): void
    {
        $operation = $this->createOperation('czk');

        self::assertSame('czk', (string)$operation->getParam(Param::MD()));
    }

    /**
     * @dataProvider merchantData
     */
    public function testMerchantDataAlwaysGetsGatewayPrefix(?string $gateway, string $data, string $expected): void
    {
        $operation = $this->createOperation($gateway);
        $operation->addParam(new Md($data));

        self::assertSame($expected, (string)$operation->getParam(Param::MD()));
    }

    /**
     * @return array<string, array{string|null, string, string}>
     */
    public static function merchantData(): array
    {
        return [
            'data equal to gateway' => ['czk', 'czk', 'czk|czk'],
            'data' => ['czk', 'order-7', 'czk|order-7'],
            'default gateway' => [null, 'order-7', '|order-7'],
            'default gateway, data like a gateway' => [null, 'czk', '|czk'],
        ];
    }

    public function testMerchantDataAddedTwiceReplacesPreviousData(): void
    {
        $operation = $this->createOperation('czk');
        $operation->addParam(new Md('first'));
        $operation->addParam(new Md('second'));

        self::assertSame('czk|second', (string)$operation->getParam(Param::MD()));
    }

    private function createOperation(?string $gateway): Operation
    {
        return new Operation(
            new OrderNumber('123456'),
            new AmountInPennies(100000),
            new Currency(CurrencyEnum::CZK()),
            $gateway
        );
    }
}
