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

namespace Pixidos\GPWebPay\Signer;

use Pixidos\GPWebPay\Config\SignerConfigProvider;

final class SignerProvider implements SignerProviderInterface
{
    /**
     * @var array<SignerInterface>
     */
    private array $signers = [];

    public function __construct(
        private readonly SignerFactoryInterface $signerFactory,
        private readonly SignerConfigProvider $configs
    ) {
    }

    /**
     * @param string $gateway
     * @return SignerInterface
     */
    public function get(string|null $gateway = null): SignerInterface
    {
        if (null === $gateway) {
            $gateway = $this->configs->getDefaultGateway();
        }
        if (array_key_exists($gateway, $this->signers)) {
            return $this->signers[$gateway];
        }

        $signer = $this->signerFactory->create($this->configs->getConfig($gateway));
        $this->signers[$gateway] = $signer;

        return $signer;
    }
}
