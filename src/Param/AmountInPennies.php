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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

class AmountInPennies implements IAmount
{
    /**
     * GP webpay AMOUNT is a numeric field of at most 15 digits.
     */
    public const MAX_VALUE = 999_999_999_999_999;

    /**
     * @var int
     */
    private int $amount;

    /**
     * @param int $amount amount in the smallest currency unit, e.g. hellers for CZK or cents for EUR
     *
     * @throws InvalidArgumentException when the amount is negative or longer than 15 digits.
     *         Zero is accepted here; whether the gateway accepts it depends on the operation.
     */
    public function __construct(int $amount)
    {
        if ($amount < 0 || $amount > self::MAX_VALUE) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s must be an integer from 0 to %d in the smallest currency unit, "%d" given.',
                    Param::AMOUNT,
                    self::MAX_VALUE,
                    $amount
                )
            );
        }

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
