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

namespace Pixidos\GPWebPay\Tests\Signer\Key;

use OpenSSLAsymmetricKey;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Signer\Key\AbstractKey;

class AbstractKeyTest extends TestCase
{
    public function testNotExistFileThrowException(): void
    {
        try {
            //@phpstan-ignore-next-line
             new class ('misssing_file') extends AbstractKey {
                protected function createKey(): OpenSSLAsymmetricKey
                {
                    throw new InvalidArgumentException('cannot create key');
                }
             };
            //@phpstan-ignore-next-line
        } catch (InvalidArgumentException $exception) {
            self::assertSame('Key file (misssing_file) not exists or not readable!', $exception->getMessage());
        }
    }
}
