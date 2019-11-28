<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Email;
use Pixidos\GPWebPay\Tests\TestHelpers;

/**
 * Class EmailTest
 * @package Pixidos\GPWebPay\Tests\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class EmailTest extends TestCase
{

    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $email = new Email('test@test.com');

        self::assertSame('test@test.com', $email->getValue());
        self::assertSame('test@test.com', (string)$email);
        self::assertSame(Param::EMAIL, $email->getParamName());
    }


    /**
     * @throws InvalidArgumentException
     */
    public function testInvalidEmailThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('EMAIL is not valid! "test(@)test.com" given.');

        new Email('test(@)test.com');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testTooLongEmailThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('EMAIL max. length is 255! "309" given.');


        new Email(TestHelpers::getLongText300() . '@test.com');
    }
}
