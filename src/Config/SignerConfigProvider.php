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

    private string $defaultGateway = 'default';

    public function addConfig(SignerConfig $config, string $gateway = 'default'): void
    {
        $this->configs[$gateway] = $config;
    }

    public function getConfig(string|null $gateway = null): SignerConfig
    {
        if (null === $gateway) {
            $gateway = $this->defaultGateway;
        }
        if (array_key_exists($gateway, $this->configs)) {
            return $this->configs[$gateway];
        }

        throw new InvalidArgumentException(
            sprintf('Config for gateway "%s" does not exist. You are probably forgot added or you wrong set default config.', $gateway)
        );
    }

    public function setDefaultGateway(string $gateway): void
    {
        $this->defaultGateway = $gateway;
    }

    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }
}
