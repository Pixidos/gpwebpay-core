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

namespace Pixidos\GPWebPay\Param;

use Stringable;

interface IParam extends Stringable
{
    /**
     * @return string
     */
    public function getParamName(): string;

    /**
     * @return int|float|string|Stringable|array<string|Stringable>
     */
    public function getValue();
}
