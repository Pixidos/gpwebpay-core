<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Tests\Signer\Key;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Signer\Key\AbstractKey;

class AbstractKeyTest extends TestCase
{

    public function testNotExistFileThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new class('misssing_file') extends AbstractKey {

            /**
             * @return resource|void
             */
            protected function createKey()
            {
                return;
            }
        };
    }
}
