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
use Pixidos\GPWebPay\Data\ResponseError;

class ResponseErrorTest extends TestCase
{

    /**
     * @dataProvider dataProvider
     * @param int    $prcode
     * @param int    $srcode
     * @param string $lang
     * @param string $expectedMsg
     */
    public function testCreate(int $prcode, int $srcode, string $lang, string $expectedMsg): void
    {
        $error = new ResponseError($prcode, $srcode);

        self::assertSame($error->getMessage($lang), $expectedMsg);
    }


    /**
     * @return array[]
     */
    public function dataProvider(): array
    {
        return [
            '3D cz' => [28, 3002, 'cz', 'Neověřeno v 3D. Vydavatel karty nebo karta není zapojena do 3D'],
            '3D en' => [28, 3000, 'en', 'Not Authenticated in 3D. Cardholder not authenticated in 3D.'],
            'zamítnuto cz' => [30, 1001, 'cz', 'Zamitnuto v autorizacnim centru, katra blokována'],
            'zamítnuto en' => [30, 1001, 'en', 'Declined in AC, Card blocked'],
        ];
    }
}
