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

class Config
{
    /**
     * @var PaymentConfigProvider
     */
    private $paymentConfigProvider;
    /**
     * @var SignerConfigProvider
     */
    private $signerConfigProvider;
    /**
     * @var string
     */
    private $defaultGateway;

    public function __construct(
        PaymentConfigProvider $paymentConfigProvider,
        SignerConfigProvider $signerConfigProvider
    ) {
        $this->paymentConfigProvider = $paymentConfigProvider;
        $this->signerConfigProvider = $signerConfigProvider;
    }

    /**
     * @return PaymentConfigProvider
     */
    public function getPaymentConfigProvider(): PaymentConfigProvider
    {
        return $this->paymentConfigProvider;
    }

    /**
     * @return SignerConfigProvider
     */
    public function getSignerConfigProvider(): SignerConfigProvider
    {
        return $this->signerConfigProvider;
    }

    /**
     * @return string
     */
    public function getDefaultGateway(): string
    {
        return $this->defaultGateway;
    }
}
