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

use Stringable;

interface SignerInterface
{
    /**
     * @param array<string, string|Stringable> $params
     *
     * @return string
     */
    public function sign(array $params): string;

    /**
     * @param array<string, string|Stringable> $params
     * @param string                           $digest
     *
     * @return bool
     */
    public function verify(array $params, string $digest): bool;
}
