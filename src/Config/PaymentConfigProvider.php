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
use Pixidos\GPWebPay\Exceptions\LogicException;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;

/**
 * Gateway keys are case-insensitive: they are stored and returned in lowercase,
 * the form Operation uses in MD.
 */
class PaymentConfigProvider
{
    /**
     * @var string defaultGatewayKey
     */
    private string $defaultGateway;
    /**
     * @var array<PaymentConfig>
     */
    private array $paymentConfigs = [];

    public function __construct(string $defaultGateway = 'default')
    {
        $this->defaultGateway = strtolower($defaultGateway);
    }

    public function addPaymentConfig(PaymentConfig $paymentConfig): void
    {
        $this->paymentConfigs[strtolower($paymentConfig->getGateway())] = $paymentConfig;
    }


    public function getUrl(string|null $gateway = null): string
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getUrl();
    }

    public function getMerchantNumber(string|null $gateway = null): MerchantNumber
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getMerchantNumber();
    }

    public function getDepositFlag(string|null $gateway = null): DepositFlag
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getDepositFlag();
    }

    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }

    public function setDefaultGateway(string $gateway): void
    {
        $this->defaultGateway = strtolower($gateway);
    }

    /**
     * @return string the configured gateway key in lowercase
     * @throws InvalidArgumentException when the gateway is not configured
     */
    public function getGateway(?string $gateway = null): string
    {
        $gateway = null === $gateway ? $this->getDefaultGateway() : strtolower($gateway);

        if (!array_key_exists($gateway, $this->paymentConfigs)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Config for key: "%s" not exist. Possible keys are: "%s"',
                    $gateway,
                    implode(', ', array_keys($this->paymentConfigs))
                )
            );
        }

        return $gateway;
    }

    public function getResponseUrl(?string $gateway = null): ?ResponseUrl
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getResponseUrl();
    }
}
