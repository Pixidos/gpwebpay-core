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

use Pixidos\GPWebPay\Exceptions\SignerException;

class PublicKey extends AbstractKey
{
    /**
     * @return resource
     * @throws SignerException
     */
    protected function createKey()
    {
        $key = openssl_pkey_get_public($this->getContent());
        if (false === $key) {
            throw new SignerException(
                sprintf('"%s" is not valid public key.', $this->file)
            );
        }

        return $this->key = $key;
    }
}
