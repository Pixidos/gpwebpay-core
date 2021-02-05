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

use function Pixidos\GPWebPay\assertIsInteger;

class Amount implements IParam
{
    /**
     * @var int
     */
    private $amount;

    /**
     * Amount constructor.
     *
     * @param float $amount
     * @param bool  $converToPennies
     *
     * @throws InvalidArgumentException
     */
    public function __construct(float $amount, bool $converToPennies = true)
    {
        // prevod na halere/centy
        if ($converToPennies) {
            $amount *= 100;
        }

        assertIsInteger($amount, 'AMOUNT');

        $this->amount = (int)$amount;
    }


    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::AMOUNT;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->amount;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->amount;
    }
}
