<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\ResponseUrl;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class ResponseUrlTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class ResponseUrlTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testCreate(): void
    {
        $responseUrl = new ResponseUrl('http://test.com');

        self::assertSame('http://test.com', (string)$responseUrl);
        self::assertSame('http://test.com', $responseUrl->getValue());
        self::assertSame(Param::RESPONSE_URL, $responseUrl->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithInvalidUrl(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URL is Invalid.');

        new ResponseUrl('tohlenenniurl');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFailedCreateWithLongText(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('URL max. length is 300! "307" given.');

        new ResponseUrl('http://' . TestHelpers::getLongText300());
    }
}
