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

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;

/**
 * Class Settings
 * @package Pixidos\GPWebPay
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
class Settings
{
    /**
     * @var string defaultGatewayKey
     */
    private $defaultGatewayKey;
    /**
     * @var Setting[]
     */
    private $settings;

    /**
     * Settings constructor.
     *
     * @param string $defaultGatewayKey
     * @param Setting ...$settings
     */
    public function __construct(string $defaultGatewayKey, Setting ...$settings)
    {

        foreach ($settings as $setting) {
            $this->settings[$setting->getGatewayKey()] = $setting;
        }
        $this->defaultGatewayKey = $defaultGatewayKey;
    }


    /**
     * @param string $gatewayKey
     *
     * @return string
     */
    public function getUrl(string $gatewayKey): string
    {
        return $this->settings[$gatewayKey]->getUrl();
    }

    /**
     * @param string $gatewayKey
     *
     * @return MerchantNumber
     */
    public function getMerchantNumber(string $gatewayKey): MerchantNumber
    {
        return $this->settings[$gatewayKey]->getMerchantNumber();
    }

    /**
     * @param string $gatewayKey
     *
     * @return string
     */
    public function getPrivateKey(string $gatewayKey): string
    {

        return $this->settings[$gatewayKey]->getPrivateKey();
    }

    /**
     * @param string $gatewayKey
     *
     * @return string
     */
    public function getPublicKey(string $gatewayKey): string
    {
        return $this->settings[$gatewayKey]->getPublicKey();
    }

    /**
     * @param string $gatewayKey
     *
     * @return string
     */
    public function getPrivateKeyPassword(string $gatewayKey): string
    {
        return $this->settings[$gatewayKey]->getPrivateKeyPassword();
    }

    /**
     * @param string $gatewayKey
     *
     * @return DepositFlag
     */
    public function getDepositFlag(string $gatewayKey): DepositFlag
    {
        return $this->settings[$gatewayKey]->getDepositFlag();
    }

    /**
     * @return string
     */
    public function getDefaultGatewayKey(): string
    {
        return $this->defaultGatewayKey;
    }

    /**
     * @param null|string $gatewayKey
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function getGatewayKey(?string $gatewayKey = null): string
    {
        if ($gatewayKey === null) {
            $gatewayKey = $this->getDefaultGatewayKey(); //czk config default
        }

        if (!array_key_exists($gatewayKey, $this->settings)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Setting for key: "%s" not exist. Possible keys are: "%s"',
                    $gatewayKey,
                    implode(', ', array_keys($this->settings))
                )
            );
        }

        return $gatewayKey;
    }

}
