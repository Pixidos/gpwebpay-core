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

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Lang;

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
