<?php declare(strict_types=1);

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
     * @param  null|string $gatewayKey
     *
     * @return ISigner
     * @throws SignerException
     */
    public function create(?string $gatewayKey = null): ISigner;
}
