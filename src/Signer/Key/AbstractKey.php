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

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;

abstract class AbstractKey
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var resource|null on PHP 8 OpenSSLAsymmetricKey|null
     */
    protected $key;

    public function __construct(string $file)
    {
        $this->verifyFile($file);
        $this->file = $file;
    }

    /**
     * @return resource on PHP 8 return OpenSSLAsymmetricKey
     */
    public function getKey()
    {
        if (null !== $this->key) {
            return $this->key;
        }

        return $this->key = $this->createKey();
    }

    /**
     * @param string $file
     *
     */
    protected function verifyFile(string $file): void
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new InvalidArgumentException(sprintf('Key file (%s) not exists or not readable!', $file));
        }
    }

    protected function getContent(): string
    {
        $content = file_get_contents($this->file);
        if (false === $content) {
            throw new SignerException(sprintf('Failed open file with key "%s"', $this->file));
        }

        return $content;
    }

    /**
     * @return resource on PHP 8 return OpenSSLAsymmetricKey
     */
    abstract protected function createKey();
}
