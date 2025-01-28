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
     * @param array<string, mixed>  $params
     * @param string $defaultGateway
     * @return array<string, array<string, string|int>>
     */
    private function normalizeParams(array $params, string $defaultGateway): array
    {
        $defaultGateway = strtolower($defaultGateway);

        if (!array_key_exists(self::PRIVATE_KEY, $params)) {
            //not multiple config

            /**
             * @phpstan-ignore-next-line
             */
            return $params;
        }
        $data = [];
        $data[$defaultGateway] = $params;

        /**
         * @phpstan-ignore-next-line
         */
        return $data;
    }


    /**
     * @param string                    $key
     * @param array<string, int|string> $data
     * @param string                    $gateway
     *
     * @return int|string
     * @throws InvalidArgumentException
     */
    private function getValue(string $key, array $data, string $gateway): int|string
    {
        if (!array_key_exists($key, $data)) {
            throw new InvalidArgumentException(sprintf('Missing key:"%s" in %s configuration', $key, $gateway));
        }

        return $data[$key];
    }


    /**
     * @param string $defaultGateway
     * @return Config
     */
    private function createConfig(string $defaultGateway): Config
    {
        $paymentConfigProvider = new PaymentConfigProvider();
        $paymentConfigProvider->setDefaultGateway($defaultGateway);
        $signerConfigProvider = new SignerConfigProvider();

        return new Config($paymentConfigProvider, $signerConfigProvider);
    }

    /**
     * @param array<array<string, int|string>> $params
     * @param Config                           $config
     */
    private function processParams(array $params, Config $config): void
    {
        $paymentConfig = $config->getPaymentConfigProvider();
        $signerConfig = $config->getSignerConfigProvider();
        foreach ($params as $gateway => $data) {
            $paymentConfig->addPaymentConfig(
                $this->paymentConfigFactory->create(
                    (string)$this->getValue(self::URL, $data, $gateway),
                    (string)$this->getValue(self::MERCHANT_NUMBER, $data, $gateway),
                    array_key_exists(self::DEPOSIT_FLAG, $data) ? (int)$data[self::DEPOSIT_FLAG] : 1,
                    $gateway,
                    (string)($data[self::RESPONSE_URL] ?? null)
                )
            );
            $signerConfig->addConfig(
                new SignerConfig(
                    (string)$this->getValue(self::PRIVATE_KEY, $data, $gateway),
                    (string)$this->getValue(self::PRIVATE_KEY_PASSPHRASE, $data, $gateway),
                    (string)$this->getValue(self::PUBLIC_KEY, $data, $gateway)
                ),
                $gateway
            );
        }
    }
}
