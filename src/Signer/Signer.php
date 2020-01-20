<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Signer;

use Pixidos\GPWebPay\Exceptions\SignerException;

class Signer implements ISigner
{

    /** @var string */
    private $privateKey;

    /** @var resource|null */
    private $privateKeyResource;

    /** @var string */
    private $privateKeyPassword;

    /** @var string */
    private $publicKey;

    /** @var resource|null */
    private $publicKeyResource;


    /**
     * Signer constructor.
     *
     * @param string $privateKey
     * @param string $privateKeyPassword
     * @param string $publicKey
     *
     * @throws SignerException
     */
    public function __construct(string $privateKey, string $privateKeyPassword, string $publicKey)
    {

        if (!file_exists($privateKey) || !is_readable($privateKey)) {
            throw new SignerException(sprintf('Private key (%s) not exists or not readable!', $privateKey));
        }

        if (!file_exists($publicKey) || !is_readable($publicKey)) {
            throw new SignerException(sprintf('Public key (%s) not exists or not readable!', $publicKey));
        }

        $this->privateKey = $privateKey;
        $this->privateKeyPassword = $privateKeyPassword;
        $this->publicKey = $publicKey;
    }

    /**
     * @param array $params
     *
     * @return string
     * @throws SignerException
     */
    public function sign(array $params): string
    {
        $digestText = implode('|', $params);
        openssl_sign($digestText, $digest, $this->getPrivateKeyResource());
        $digest = base64_encode($digest);

        return $digest;
    }

    /**
     * @param array $params
     * @param string $digest
     *
     * @return bool
     * @throws SignerException
     */
    public function verify(array $params, string $digest): bool
    {
        $data = implode('|', $params);
        $decode = (string)base64_decode($digest, true);
        $ok = openssl_verify($data, $decode, $this->getPublicKeyResource());

        return $ok === 1;
    }

    /**
     * @return resource
     * @throws SignerException
     */
    private function getPrivateKeyResource()
    {
        if ($this->privateKeyResource !== null) {
            return $this->privateKeyResource;
        }
        $privateKey = file_get_contents($this->privateKey);
        if ($privateKey === false) {
            throw new SignerException(sprintf('Failed open file with private key "%s"', $this->privateKey));
        }
        $privateKeyResource = openssl_pkey_get_private($privateKey, $this->privateKeyPassword);
        if ($privateKeyResource === false) {
            throw new SignerException(sprintf('"%s" is not valid PEM private key (or password is incorrect).', $this->privateKey));
        }

        return $this->privateKeyResource = $privateKeyResource;
    }

    /**
     * @return resource
     * @throws SignerException
     */
    private function getPublicKeyResource()
    {
        if ($this->publicKeyResource !== null) {
            return $this->publicKeyResource;
        }
        $publicKey = file_get_contents($this->publicKey);
        if ($publicKey === false) {
            throw new SignerException(sprintf('Failed open file with public key "%s"', $this->publicKey));
        }

        $publicKeyResource = openssl_pkey_get_public($publicKey);
        if ($publicKeyResource === false) {
            throw new SignerException(sprintf('"%s" is not valid PEM public key.', $this->publicKey));
        }

        return $this->publicKeyResource = $publicKeyResource;
    }
}
