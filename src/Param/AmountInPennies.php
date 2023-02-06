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

use Pixidos\GPWebPay\Enum\Param;

class AmountInPennies implements IAmount
{
    /**
     * @var int
     */
    private int $amount;

    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }


    public function getParamName(): string
    {
        return Param::AMOUNT;
    }


    public function getValue(): int
    {
        return $this->amount;
    }


    public function __toString(): string
    {
        return (string)$this->amount;
    }
}
