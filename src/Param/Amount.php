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
     * @param int|float $amount
     * @param bool $converToPennies
     *
     * @throws InvalidArgumentException
     */
    public function __construct($amount, bool $converToPennies = true)
    {
        $isFloat = is_float($amount);
        if (!is_int($amount) && !$isFloat) {
            throw new InvalidArgumentException(sprintf('AMOUNT must be type of INT|FLOAT|DOUBLE ! "%s" given.', gettype($amount)));
        }

        if ($isFloat && !$converToPennies) {
            throw new InvalidArgumentException(sprintf('You dost not use AMOUNT as FLOAT|DOUBLE withou converToPennies=true  ! "%s" given.', gettype($amount)));
        }
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
