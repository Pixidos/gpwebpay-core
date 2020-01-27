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
use function Pixidos\GPWebPay\assertMaxLenght;

class FastPayId implements IParam
{
    /**
     * @var int|float|string $value
     */
    private $value;

    /**
     * FastPayId constructor.
     * @param int|float|string $fastPayId max. lenght is 15 and can contain only numbers
     *
     * @throws InvalidArgumentException
     */
    public function __construct($fastPayId)
    {
        assertIsInteger($fastPayId, $this->getParamName());
        assertMaxLenght($fastPayId, 15, $this->getParamName());
        $this->value = $fastPayId;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function getParamName(): string
    {
        return Param::FASTPAYID;
    }

    /**
     * @return int|float|string
     */
    public function getValue()
    {
        return $this->value;
    }

}
