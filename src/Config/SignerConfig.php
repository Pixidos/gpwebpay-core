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

namespace Pixidos\GPWebPay\Config;

class SignerConfig
{
    public function __construct(
            private readonly string $privateKey,
            private readonly string $privateKeyPassword,
            private readonly string $publicKey
    ) {
    }

    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    public function getPrivateKeyPassword(): string
    {
        return $this->privateKeyPassword;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }
}
