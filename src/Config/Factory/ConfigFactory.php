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
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Config\SignerConfig;
use Pixidos\GPWebPay\Config\SignerConfigProvider;

/**
 * @phpstan-import-type ConfigParams from ConfigFactoryInterface
 * @phpstan-import-type GatewayConfig from ConfigFactoryInterface
 */
class ConfigFactory implements ConfigFactoryInterface
{
    private PaymentConfigFactory $paymentConfigFactory;

    public function __construct(PaymentConfigFactory $paymentConfigFactory)
    {
        $this->paymentConfigFactory = $paymentConfigFactory;
    }

    public function create(array $params, string $defaultGateway = 'default'): Config
    {
        $data = $this->normalizeParams($params, $defaultGateway);
        $config = $this->createConfig($defaultGateway);
        $this->processParams($data, $config);

        return $config;
    }

    /**
     * @phpstan-param ConfigParams $params
     * @return array<string, GatewayConfig>
     */
    private function normalizeParams(array $params, string $defaultGateway): array
    {
        $defaultGateway = strtolower($defaultGateway);

        // Pokud je to přímo GatewayConfig (ne array<string, GatewayConfig>)
        if (array_key_exists(self::PRIVATE_KEY, $params)) {
            /** @var GatewayConfig $params */
            return [$defaultGateway => $params];
        }

        /** @phpstan-var array<string, GatewayConfig> $params */
        return $params;
    }

    private function createConfig(string $defaultGateway): Config
    {
        $paymentConfigProvider = new PaymentConfigProvider();
        $paymentConfigProvider->setDefaultGateway($defaultGateway);
        $signerConfigProvider = new SignerConfigProvider();

        return new Config($paymentConfigProvider, $signerConfigProvider);
    }

    /**
     * @phpstan-param array<string, GatewayConfig> $params
     */
    private function processParams(array $params, Config $config): void
    {
        $paymentConfig = $config->getPaymentConfigProvider();
        $signerConfig = $config->getSignerConfigProvider();
        foreach ($params as $gateway => $data) {
            $paymentConfig->addPaymentConfig(
                $this->paymentConfigFactory->create(
                    $data[self::URL],
                    $data[self::MERCHANT_NUMBER],
                    $data[self::DEPOSIT_FLAG],
                    $gateway,
                    (string)($data[self::RESPONSE_URL] ?? null)
                )
            );
            $signerConfig->addConfig(
                new SignerConfig(
                    $data[self::PRIVATE_KEY],
                    $data[self::PRIVATE_KEY_PASSPHRASE],
                    $data[self::PUBLIC_KEY]
                ),
                $gateway
            );
        }
    }
}
