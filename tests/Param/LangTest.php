<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Lang;
use PHPUnit\Framework\TestCase;

class LangTest extends TestCase
{

    public function testSuccessCreate(): void
    {
        $lang = new Lang('cs');

        self::assertSame('cs', $lang->getValue());
        self::assertSame('cs', (string)$lang);
        self::assertSame(Param::LANG, $lang->getParamName());
    }


    public function testInvalidLangThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('LANG max. length is 2! "3" given.');

        new Lang('czk');
    }
}
