<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Settings;

use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;

class Setting
{
    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var string
     */
    private $privateKeyPassword;
    /**
     * @var string
     */
    private $publicKey;
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
    private $gatewayKey;

    /**
     * Settings constructor.
     *
     * @param string $privateKey
     * @param string $privateKeyPassword
     * @param string $publicKey
     * @param string $url
     * @param MerchantNumber $merchantNumber
     * @param DepositFlag $depositFlag
     * @param string $gatewayKey
     */
    public function __construct(
        string $privateKey,
        string $privateKeyPassword,
        string $publicKey,
        string $url,
        MerchantNumber $merchantNumber,
        DepositFlag $depositFlag,
        string $gatewayKey
    ) {

        $this->privateKey = $privateKey;
        $this->privateKeyPassword = $privateKeyPassword;
        $this->publicKey = $publicKey;
        $this->url = $url;
        $this->merchantNumber = $merchantNumber;
        $this->depositFlag = $depositFlag;
        $this->gatewayKey = $gatewayKey;
    }

    /**
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->privateKey;
    }

    /**
     * @return string
     */
    public function getPrivateKeyPassword(): string
    {
        return $this->privateKeyPassword;
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->publicKey;
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
     * @return string
     */
    public function getGatewayKey(): string
    {
        return $this->gatewayKey;
    }

}
