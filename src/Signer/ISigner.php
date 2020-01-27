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

interface ISigner
{
    /**
     * @param array $params
     *
     * @return string
     */
    public function sign(array $params): string;

    /**
     * @param array $params
     * @param string $digest
     *
     * @return bool
     */
    public function verify(array $params, string $digest): bool;
}
