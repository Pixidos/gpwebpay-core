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

class OrderNumber implements IParam
{

    private $value;

    /**
     * OrderNumber constructor.
     *
     * @param int|float|string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct($value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getParamName(): string
    {
        return Param::ORDERNUMBER;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @param int|float|string $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate($value): void
    {
        assertIsInteger($value, $this->getParamName());
        assertMaxLenght($value, 15, $this->getParamName());
    }

}
