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

namespace Pixidos\GPWebPay\Signer;

use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\Key\PrivateKey;
use Pixidos\GPWebPay\Signer\Key\PublicKey;
use Stringable;

class Signer implements SignerInterface
{
    public function __construct(
        private readonly PrivateKey $privateKey,
        private readonly PublicKey $publicKey,
        private readonly string|int $algorithm = OPENSSL_ALGO_SHA1
    ) {
    }

    /**
     * @param array<string, string|Stringable> $params
     *
     * @return string
     * @throws SignerException
     */
    public function sign(array $params): string
    {
        $digestText = implode('|', $params);
        openssl_sign($digestText, $digest, $this->privateKey->getKey(), $this->algorithm);

        return base64_encode($digest);
    }

    /**
     * @param array<string, string|Stringable> $params
     * @param string                           $digest
     *
     * @return bool
     * @throws SignerException
     */
    public function verify(array $params, string $digest): bool
    {
        $data = implode('|', $params);
        $decode = (string)base64_decode($digest, true);
        $ok = openssl_verify($data, $decode, $this->publicKey->getKey(), $this->algorithm);

        return 1 === $ok;
    }
}
