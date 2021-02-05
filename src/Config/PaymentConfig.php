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
    /**
     * @var string
     */
    private $url;
    /**
     * @var MerchantNumber
     */
    private $merchantNumber;
    /**
     * @var DepositFlag
     */
    private $depositFlag;
    /**
     * @var string
     */
    private $gateway;
    /**
     * @var ResponseUrl|null
     */
    private $responseUrl;

    /**
     * Settings constructor.
     * @TODO: url as object
     * @param string           $url
     * @param MerchantNumber   $merchantNumber
     * @param DepositFlag      $depositFlag
     * @param string           $gateway
     * @param ResponseUrl|null $responseUrl
     */
    public function __construct(
        string $url,
        MerchantNumber $merchantNumber,
        DepositFlag $depositFlag,
        string $gateway,
        ?ResponseUrl $responseUrl = null
    ) {
        $this->url = $url;
        $this->merchantNumber = $merchantNumber;
        $this->depositFlag = $depositFlag;
        $this->gateway = $gateway;
        $this->responseUrl = $responseUrl;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return MerchantNumber
     */
    public function getMerchantNumber(): MerchantNumber
    {
        return $this->merchantNumber;
    }

    /**
     * @return DepositFlag
     */
    public function getDepositFlag(): DepositFlag
    {
        return $this->depositFlag;
    }

    /**
     * @return ResponseUrl|null
     */
    public function getResponseUrl(): ?ResponseUrl
    {
        return $this->responseUrl;
    }


    /**
     * @return string
     */
    public function getGateway(): string
    {
        return $this->gateway;
    }
}
