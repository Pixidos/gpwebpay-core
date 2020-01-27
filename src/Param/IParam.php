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

namespace Pixidos\GPWebPay\Param;

interface IParam
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return string
     */
    public function getParamName(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
