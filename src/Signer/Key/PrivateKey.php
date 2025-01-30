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

namespace Pixidos\GPWebPay\Signer\Key;

use OpenSSLAsymmetricKey;
use Pixidos\GPWebPay\Exceptions\SignerException;

final class PrivateKey extends AbstractKey
{
    public function __construct(
        string $file,
        private readonly string $password
    ) {
        parent::__construct($file);
    }


    /**
     * @throws SignerException
     */
    protected function createKey(): OpenSSLAsymmetricKey
    {
        $content = $this->getContent();
        $key = openssl_pkey_get_private($content, $this->password);
        if (false === $key) {
            throw new SignerException(
                sprintf('"%s" is not valid PEM private key (or password is incorrect).', $this->file)
            );
        }

        return $key;
    }
}
