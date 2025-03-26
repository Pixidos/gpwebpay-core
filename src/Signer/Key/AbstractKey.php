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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;

abstract class AbstractKey
{
    protected OpenSSLAsymmetricKey|null $key = null;

    /**
     * @param string $file
     * @throws InvalidArgumentException
     */
    public function __construct(
        protected readonly string $file
    ) {
        $this->verifyFile($file);
    }


    public function getKey(): OpenSSLAsymmetricKey
    {
        if (null !== $this->key) {
            return $this->key;
        }

        return $this->key = $this->createKey();
    }

    /**
     * @param string $file
     * @throws InvalidArgumentException
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

    abstract protected function createKey(): OpenSSLAsymmetricKey;
}
