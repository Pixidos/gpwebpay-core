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

/**
 * Gateway keys are case-insensitive and stored in lowercase, like in PaymentConfigProvider.
 */
class SignerConfigProvider
{
    /**
     * @var array<SignerConfig>
     */
    private array $configs = [];

    private string $defaultGateway = 'default';

    public function addConfig(SignerConfig $config, string $gateway = 'default'): void
    {
        $this->configs[strtolower($gateway)] = $config;
    }

    /**
     * @throws InvalidArgumentException when the gateway is not configured
     */
    public function getConfig(string|null $gateway = null): SignerConfig
    {
        $gateway = null === $gateway ? $this->defaultGateway : strtolower($gateway);
        if (array_key_exists($gateway, $this->configs)) {
            return $this->configs[$gateway];
        }

        throw new InvalidArgumentException(
            sprintf('Config for gateway "%s" does not exist. You are probably forgot added or you wrong set default config.', $gateway)
        );
    }

    public function setDefaultGateway(string $gateway): void
    {
        $this->defaultGateway = strtolower($gateway);
    }

    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }
}
