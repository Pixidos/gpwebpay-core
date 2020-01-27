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

namespace Pixidos\GPWebPay\Signer;

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Settings\Settings;

class SignerFactory implements ISignerFactory
{
    /**
     * @var ISigner[]
     */
    private $signers = [];

    /** @var Settings $settings */
    private $settings;

    /**
     * SignerFactory constructor.
     *
     * @param Settings $settings
     */
    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param null|string $gatewayKey
     *
     * @return ISigner
     * @throws InvalidArgumentException
     *
     * @throws SignerException
     */
    public function create(?string $gatewayKey = null): ISigner
    {
        $key = $this->settings->getGatewayKey($gatewayKey);
        if (array_key_exists($key, $this->signers)) {
            return $this->signers[$key];
        }

        return $this->signers[$key] = new Signer(
            $this->settings->getPrivateKey($key),
            $this->settings->getPrivateKeyPassword($key),
            $this->settings->getPublicKey($key)
        );
    }
}
