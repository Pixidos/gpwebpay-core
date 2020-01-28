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

use Pixidos\GPWebPay\Config\SignerConfig;
use Pixidos\GPWebPay\Signer\Key\PrivateKey;
use Pixidos\GPWebPay\Signer\Key\PublicKey;

final class SignerFactory implements SignerFactoryInterface
{

    /**
     * @param SignerConfig $config
     * @return SignerInterface
     */
    public function create(SignerConfig $config): SignerInterface
    {
        return new Signer(
            new PrivateKey($config->getPrivateKey(), $config->getPrivateKeyPassword()),
            new PublicKey($config->getPublicKey())
        );
    }
}
