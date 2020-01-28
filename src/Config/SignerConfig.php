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

namespace Pixidos\GPWebPay\Config;

class SignerConfig
{

    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var string
     */
    private $privateKeyPassword;
    /**
     * @var string
     */
    private $publicKey;

    /**
     * Config constructor.
     * @param string $privateKey
     * @param string $privateKeyPassword
     * @param string $publicKey
     */
    public function __construct(string $privateKey, string $privateKeyPassword, string $publicKey)
    {
        $this->privateKey = $privateKey;
        $this->privateKeyPassword = $privateKeyPassword;
        $this->publicKey = $publicKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getPrivateKeyPassword(): string
    {
        return $this->privateKeyPassword;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
    }


}
