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

final class PrivateKey extends AbstractKey
{
    /**
     * @var string
     */
    private $password;


    /**
     * PrivateKey constructor.
     * @param string $file
     * @param string $password
     */
    public function __construct(string $file, string $password)
    {
        parent::__construct($file);
        $this->password = $password;
    }


    /**
     * @return resource
     * @throws SignerException
     */
    protected function createKey()
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
