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

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

class SignerConfigProvider
{
    /**
     * @var array<SignerConfig>
     */
    private array $configs = [];


    public function addConfig(SignerConfig $config, string $gateway): void
    {
        $this->configs[$gateway] = $config;
    }

    public function getConfig(string $gateway): SignerConfig
    {
        if (array_key_exists($gateway, $this->configs)) {
            return $this->configs[$gateway];
        }

        throw new InvalidArgumentException(
            sprintf('Config for gateway "%s" does not exist. You are probably forgot added.', $gateway)
        );
    }
}
