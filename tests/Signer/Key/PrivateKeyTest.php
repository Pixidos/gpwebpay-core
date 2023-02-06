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

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\Key\PrivateKey;
use ReflectionClass;
use ReflectionException;

class PrivateKeyTest extends TestCase
{
    public function testCreate(): void
    {
        new PrivateKey(__DIR__ . '/../../_certs/test.pem', '1234567');

        $this->expectNotToPerformAssertions();
    }

    public function testReturnSameResource(): void
    {
        $privateKey = new PrivateKey(__DIR__ . '/../../_certs/test.pem', '1234567');

        $res1 = $privateKey->getKey();
        $res2 = $privateKey->getKey();

        self::assertSame($res1, $res2);
    }

    public function testWrongPasswordThrowException(): void
    {
        $file = __DIR__ . '/../../_certs/test.pem';

        $this->expectException(SignerException::class);
        $this->expectExceptionMessage('"' . $file . '" is not valid PEM private key (or password is incorrect).');
        $privateKey = new PrivateKey($file, '123567');

        $privateKey->getKey();
    }

    /**
     * @throws ReflectionException
     */
    public function testReadFileThrowException(): void
    {
        $this->expectException(SignerException::class);
        $this->expectExceptionMessage('Failed open file with key "test"');

        /** @var PrivateKey $privateKey */
        $privateKey = (new ReflectionClass(PrivateKey::class))->newInstanceWithoutConstructor();
        $reflection = new ReflectionClass($privateKey);
        $file = $reflection->getProperty('file');
        $file->setAccessible(true);
        $file->setValue($privateKey, 'test');
        //PHPUnit hack ->
        error_reporting(E_ALL ^ E_WARNING);
        $privateKey->getKey();
        error_reporting(E_ALL);
    }
}
