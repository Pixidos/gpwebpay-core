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

namespace Pixidos\GPWebPay\Tests\Exceptions;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;

class GPWebPayResultExceptionTest extends TestCase
{

    public function testCreate():  void
    {
        $exception = new GPWebPayResultException('', 30, 1001, 'resulttext');

        self::assertSame(30, $exception->getPrcode());
        self::assertSame(1001, $exception->getSrcode());
        self::assertSame('resulttext', $exception->getResultText());

        self::assertSame('Zamitnuto v autorizacnim centru, katra blokována', $exception->translate('cz'));
        self::assertSame('Declined in AC, Card blocked', $exception->translate('en'));
    }
}
