<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\PanPattern;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class PanPatternTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class PanPatternTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $panPattern = new PanPattern('panPattern');

        self::assertSame('panPattern', (string)$panPattern);
        self::assertSame('panPattern', $panPattern->getValue());
        self::assertSame(Param::PANPATTERN, $panPattern->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('PANPATTERN max. length is 255! "300" given.');

        new PanPattern(TestHelpers::getLongText300());
    }
}
