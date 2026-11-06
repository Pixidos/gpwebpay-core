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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

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

    /**
     * Gateway keys are case-insensitive and normalized to lowercase, the form Operation sends in MD.
     */
    public function create(array $params, string $defaultGateway = 'default'): Config
    {
        $defaultGateway = $this->normalizeGateway($defaultGateway);
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
        // a single GatewayConfig, not array<string, GatewayConfig>
        if (array_key_exists(self::PRIVATE_KEY, $params)) {
            /** @var GatewayConfig $params */
            return [$defaultGateway => $params];
        }

        /** @phpstan-var array<array-key, GatewayConfig> $params */
        $normalized = [];
        foreach ($params as $gateway => $data) {
            $key = $this->normalizeGateway((string)$gateway);
            if (array_key_exists($key, $normalized)) {
                throw new InvalidArgumentException(
                    sprintf('Gateway keys are case-insensitive, "%s" is configured more than once.', $key)
                );
            }
            $normalized[$key] = $data;
        }

        if (!array_key_exists($defaultGateway, $normalized)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The key for defautlGateway: "%s" is not included in the configuration parameters. Keys in the parameters "%s"',
                    $defaultGateway,
                    implode(', ', array_keys($params))
                )
            );
        }

        return $normalized;
    }

    /**
     * @throws InvalidArgumentException
     */
    private function normalizeGateway(string $gateway): string
    {
        // the gateway key is sent in MD, which allows printable ASCII only;
        // "|" separates merchant data and "#" starts the suffix GP webpay may append
        if (1 !== preg_match('~^[\x20-\x7E]+$~D', $gateway) || strpbrk($gateway, '|#') !== false) {
            throw new InvalidArgumentException(
                sprintf('Gateway key "%s" must be printable ASCII without "|" and "#".', $gateway)
            );
        }

        return strtolower($gateway);
    }

    private function createConfig(string $defaultGateway): Config
    {
        $paymentConfigProvider = new PaymentConfigProvider($defaultGateway);
        $signerConfigProvider = new SignerConfigProvider();
        $signerConfigProvider->setDefaultGateway($defaultGateway);

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
