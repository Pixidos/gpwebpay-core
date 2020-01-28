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
     * @var resource|null
     */
    protected $key;

    public function __construct(string $file)
    {
        $this->verifyFile($file);
        $this->file = $file;
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
        if ($content === false) {
            throw new SignerException(sprintf('Failed open file with key "%s"', $this->file));
        }

        return $content;
    }

    /**
     * @return resource
     */
    public function getKey()
    {
        if ($this->key !== null) {
            return $this->key;
        }

        return $this->key = $this->createKey();
    }

    /**
     * @return resource
     */
    abstract protected function createKey();
}
