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

use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;

class PaymentConfig
{
    public function __construct(
        private readonly string $url,
        private readonly MerchantNumber $merchantNumber,
        private readonly DepositFlag $depositFlag,
        private readonly string $gateway,
        private readonly ResponseUrl|null $responseUrl = null
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMerchantNumber(): MerchantNumber
    {
        return $this->merchantNumber;
    }

    public function getDepositFlag(): DepositFlag
    {
        return $this->depositFlag;
    }

    public function getResponseUrl(): ?ResponseUrl
    {
        return $this->responseUrl;
    }

    public function getGateway(): string
    {
        return $this->gateway;
    }
}
