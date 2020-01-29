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

namespace Pixidos\GPWebPay\Config\Factory;

use Pixidos\GPWebPay\Config\Config;
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Config\SignerConfig;
use Pixidos\GPWebPay\Config\SignerConfigProvider;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

class ConfigFactory implements ConfigFactoryInterface
{
    public const PRIVATE_KEY = 'privateKey';
    public const URL = 'url';
    public const MERCHANT_NUMBER = 'merchantNumber';
    public const DEPOSIT_FLAG = 'depositFlag';
    public const PRIVATE_KEY_PASSWORD = 'privateKeyPassword';
    public const PUBLIC_KEY = 'publicKey';
    public const RESPONSE_URL = 'responseUrl';


    /**
     * @var PaymentConfigFactory
     */
    private $paymentConfigFactory;

    public function __construct(PaymentConfigFactory $paymentConfigFactory)
    {
        $this->paymentConfigFactory = $paymentConfigFactory;
    }

    /**
     * @param array  $params
     * @param string $defaultGateway
     * @return Config
     */
    public function create(array $params, string $defaultGateway = 'default'): Config
    {
        $defaultGateway = strtolower($defaultGateway);
        $data = [];
        if (array_key_exists(self::PRIVATE_KEY, $params)) {
            //not multiple config
            $data[$defaultGateway] = $params;
        } else {
            $data = $params;
        }


        $config = $this->createConfig($defaultGateway);
        $this->processParams($data, $config);

        return $config;
    }


    /**
     * @param string $key
     * @param array  $data
     * @param string $gateway
     *
     * @return int|string
     * @throws InvalidArgumentException
     */
    private function getValue(string $key, array $data, string $gateway)
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
     * @param array  $params
     * @param Config $config
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
                    (string)$this->getValue(self::PRIVATE_KEY_PASSWORD, $data, $gateway),
                    (string)$this->getValue(self::PUBLIC_KEY, $data, $gateway)
                ),
                $gateway
            );
        }
    }
}
