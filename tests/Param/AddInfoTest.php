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

namespace Pixidos\GPWebPay\Tests\Param;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\AddInfo;
use Pixidos\GPWebPay\Tests\TestHelpers;

class AddInfoTest extends TestCase
{


    /**
     * @throws InvalidArgumentException
     */
    public function testSuccessCreate(): void
    {
        $addInfo = new AddInfo('<xml></xml>');

        self::assertSame('<xml></xml>', $addInfo->getValue());
        self::assertSame('<xml></xml>', (string)$addInfo);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testStringLonger24000CharsThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ADDINFO max. length is 24000! "30011" given.');
        $xml = '<xml>' . TestHelpers::getLongText30000() . '</xml>';


        new AddInfo($xml);
    }

}
