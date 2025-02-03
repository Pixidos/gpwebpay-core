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

//    public function testReadFileThrowException(): void
//    {
//        $this->expectException(SignerException::class);
//        $testFile = sys_get_temp_dir() . '/test_key_' . uniqid();
//        $this->expectExceptionMessage('Failed open file with key "' . $testFile . '"');
//
//        touch($testFile);
//        chmod($testFile, 0400);
//
//        try {
//            // Mock ve správném namespace kde se volá file_get_contents
//            $mock = $this->getFunctionMock('Pixidos\\GPWebPay\\Signer\\Key', 'file_get_contents');
//
//            // Ověříme kde se bude volat file_get_contents
//            $reflection = new \ReflectionMethod(PrivateKey::class, 'getContent');
//            var_dump("Method getContent is in namespace: " . $reflection->getDeclaringClass()->getNamespaceName());
//
//            $mock->expects(self::exactly(2))->willReturn(false);
//
//            // Test zda mock funguje přímo v cílovém namespace
//            $result = \Pixidos\GPWebPay\Signer\Key\file_get_contents($testFile);
//            var_dump("Direct namespace call result:", $result);
//
//            $privateKey = new PrivateKey($testFile, 'password');
//            $privateKey->getKey();
//        } finally {
//            chmod($testFile, 0666);
//            unlink($testFile);
//        }
//    }
}
