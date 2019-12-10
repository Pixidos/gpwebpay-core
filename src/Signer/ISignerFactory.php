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

namespace Pixidos\GPWebPay\Signer;

use Pixidos\GPWebPay\Exceptions\SignerException;

/**
 * Interface ISignerFactory
 * @package Pixidos\GPWebPay\Intefaces
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
interface ISignerFactory
{
    /**
     * @param null|string $gatewayKey
     *
     * @return ISigner
     * @throws SignerException
     */
    public function create(?string $gatewayKey = null): ISigner;
}
