<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertMaxLenght;

class MerchantNumber implements IParam
{
    /**
     * @var string
     */
    private $value;

    /**
     * MerchantNumber constructor.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        assertMaxLenght($value, 10, $this->getParamName());

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getParamName(): string
    {
        return Param::MERCHANTNUMBER;
    }
}
