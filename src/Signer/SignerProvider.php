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

use Pixidos\GPWebPay\Config\SignerConfigProvider;

final class SignerProvider implements SignerProviderInterface
{

    /**
     * @var SignerFactoryInterface
     */
    private $signerFactory;
    /**
     * @var SignerConfigProvider
     */
    private $configs;
    /**
     * @var SignerInterface[]
     */
    private $signers = [];

    public function __construct(SignerFactoryInterface $signerFactory, SignerConfigProvider $configs)
    {
        $this->signerFactory = $signerFactory;
        $this->configs = $configs;
    }

    /**
     * @param string $gateway
     * @return SignerInterface
     */
    public function get(string $gateway): SignerInterface
    {
        if (array_key_exists($gateway, $this->signers)) {
            return $this->signers[$gateway];
        }

        $signer = $this->signerFactory->create($this->configs->getConfig($gateway));
        $this->signers[$gateway] = $signer;

        return $signer;
    }
}
