<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Signer;

/**
 * Class ISigner
 * @package Pixidos\GPWebPay\Intefaces
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
interface ISigner
{
    /**
     * @param array $params
     *
     * @return string
     */
    public function sign(array $params): string;

    /**
     * @param array  $params
     * @param string $digest
     *
     * @return bool
     */
    public function verify(array $params, string $digest): bool;
}
