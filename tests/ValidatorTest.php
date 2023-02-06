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

use Generator;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

use function Pixidos\GPWebPay\assertIsInteger;

class ValidatorTest extends TestCase
{
    /**
     * @param mixed $value
     *
     * @throws InvalidArgumentException
     * @dataProvider getIsIntegerValue
     */
    public function testAssertIsIntegerSuccessValidation($value): void
    {
        assertIsInteger($value, 'TEST');

        $this->expectNotToPerformAssertions();
    }

    /**
     * @param string|int|float $value
     *
     * @throws InvalidArgumentException
     * @dataProvider getIsIntegerInvalidValue
     */
    public function testAssertIsIntegerThrowExceptionn($value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('TEST must be integer "%s" given.', $value));

        assertIsInteger($value, 'TEST');
    }


    /**
     * @return Generator<mixed>
     */
    public function getIsIntegerValue(): Generator
    {
        yield [10];
        yield ['10'];
        yield [(float)10000];
    }

    /**
     * @return Generator<mixed>
     */
    public function getIsIntegerInvalidValue(): Generator
    {
        yield ['10.0'];
        yield [10.01];
    }
}
