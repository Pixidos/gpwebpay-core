<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertIsInteger;
use function Pixidos\GPWebPay\assertMaxLenght;

class MerOrderNum implements IParam
{
    /**
     * @var float|int|string
     */
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
     * @return int|float|string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getParamName(): string
    {
        return Param::MERORDERNUM;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @param int|float|string  $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate($value): void
    {
        assertIsInteger($value, $this->getParamName());
        assertMaxLenght($value, 30, $this->getParamName());
    }
}
