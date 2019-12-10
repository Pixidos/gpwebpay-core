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

namespace Pixidos\GPWebPay\Tests\Enum;

use Generator;
use Grifart\Enum\MissingValueDeclarationException;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;

class ParamTest extends TestCase
{

    public function testUSERPARAM(): void
    {
        $param = Param::USERPARAM();

        self::assertSame('USERPARAM1', (string)$param);
        self::assertSame('USERPARAM1', $param->toScalar());
    }

    public function testRESPONSE_URL(): void
    {
        $param = Param::RESPONSE_URL();

        self::assertSame('URL', (string)$param);
        self::assertSame('URL', $param->toScalar());
    }

    /**
     * @dataProvider getParams
     *
     * @param string $value
     *
     */
    public function testSuccessCreateByValue(string $value): void
    {
        $param = Param::fromScalar($value);

        self::assertSame($value, (string)$param);
        self::assertSame($value, $param->toScalar());
    }


    public function testCreateFailWithUnknownCurrency(): void
    {
        $this->expectException(MissingValueDeclarationException::class);
        $this->expectExceptionMessage("There is no value for enum 'Pixidos\GPWebPay\Enum\Param' and scalar value 'CARD'.");

        Param::fromScalar('CARD');
    }

    /**
     * @return Generator
     */
    public function getParams(): Generator
    {
        yield [Param::MERORDERNUM];
        yield [Param::ORDERNUMBER];
        yield [Param::OPERATION];
        yield [Param::RESPONSE_URL];
        yield [Param::USERPARAM];
        yield [Param::MD];
        yield [Param::AMOUNT];
        yield [Param::FASTPAYID];
        yield [Param::CURRENCY];
        yield [Param::DEPOSITFLAG];
        yield [Param::DESCRIPTION];
        yield [Param::REFERENCENUMBER];
        yield [Param::EMAIL];
        yield [Param::DIGEST];
        yield [Param::PAYMETHOD];
        yield [Param::PAYMETHODS];
        yield [Param::DISABLEPAYMETHOD];
        yield [Param::ADDINFO];
        yield [Param::LANG];
        yield [Param::TOKEN];
        yield [Param::FAST_TOKEN];
    }
}
