<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Digest;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class DigestTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class DigestTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $digest = new Digest('digest');

        self::assertSame('digest', (string)$digest);
        self::assertSame('digest', $digest->getValue());
        self::assertSame(Param::DIGEST, $digest->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('DIGEST max. length is 2000! "3000" given.');

        new Digest(TestHelpers::getLongText3000());
    }
}
