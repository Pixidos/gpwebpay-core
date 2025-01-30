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

namespace Pixidos\GPWebPay\Config\Factory;

use Pixidos\GPWebPay\Config\Config;

/**
 * @phpstan-type GatewayConfig array{privateKey: string, url: string, merchantNumber: string, depositFlag: int, privateKeyPassphrase: string, publicKey: string, responseUrl?: string}
 * @phpstan-type ConfigParams array<string, GatewayConfig>|GatewayConfig
 */
interface ConfigFactoryInterface
{
    public const PRIVATE_KEY = 'privateKey';
    public const URL = 'url';
    public const MERCHANT_NUMBER = 'merchantNumber';
    public const DEPOSIT_FLAG = 'depositFlag';
    public const PRIVATE_KEY_PASSPHRASE = 'privateKeyPassphrase';
    public const PUBLIC_KEY = 'publicKey';
    public const RESPONSE_URL = 'responseUrl';

    /**
     * @param ConfigParams $params
     * @param string $defaultGateway
     * @return Config
     */
    public function create(array $params, string $defaultGateway = 'default'): Config;
}
