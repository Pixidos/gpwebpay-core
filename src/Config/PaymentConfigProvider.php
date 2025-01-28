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

class PaymentConfigProvider
{
    /**
     * @var string defaultGatewayKey
     */
    private string $defaultGateway = '';
    /**
     * @var array<PaymentConfig>
     */
    private array $paymentConfigs = [];

    public function addPaymentConfig(PaymentConfig $paymentConfig): void
    {
        $this->paymentConfigs[$paymentConfig->getGateway()] = $paymentConfig;
    }


    public function getUrl(string $gateway): string
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getUrl();
    }

    public function getMerchantNumber(string $gateway): MerchantNumber
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getMerchantNumber();
    }

    public function getDepositFlag(string $gateway): DepositFlag
    {
        return $this->paymentConfigs[$this->getGateway($gateway)]->getDepositFlag();
    }

    public function getDefaultGateway(): string
    {
        if ('' === $this->defaultGateway) {
            throw new LogicException(
                sprintf('You need first set default key by %s::setDefaultGateway', self::class)
            );
        }

        return $this->defaultGateway;
    }

    public function setDefaultGateway(string $gateway): void
    {
        $this->defaultGateway = $gateway;
    }

    public function getGateway(?string $gateway = null): string
    {
        if (null === $gateway) {
            $gateway = $this->getDefaultGateway();
        }

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
