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
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\Key\PublicKey;

class PublicKeyTest extends TestCase
{

    public function testCreate(): void
    {
        new PublicKey(__DIR__ . '/../../_certs/test-pub.pem');

        $this->expectNotToPerformAssertions();
    }

    public function testReturnSameResource(): void
    {
        $publicKey = new PublicKey(__DIR__ . '/../../_certs/test-pub.pem');

        $res1 = $publicKey->getKey();
        $res2 = $publicKey->getKey();

        self::assertSame($res1, $res2);
    }


    public function testReadFileThrowException(): void
    {
        $file = __DIR__ . '/../../_certs/test-pub.pem';
        $this->expectException(SignerException::class);
        $this->expectExceptionMessage('"' . $file . '" is not valid public key.');

        $publicKey = $this->getMockBuilder(PublicKey::class)
            ->setConstructorArgs([$file])
            ->setMethods(['getContent'])
            ->getMock();
        $publicKey->expects(self::once())->method('getContent')->willReturn('');

        $publicKey->getKey();
    }
}
