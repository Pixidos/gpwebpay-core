<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertMaxLenght;

class Description implements IParam
{
    /**
     * @var string
     */
    private $value;

    /**
     * Description constructor.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

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
        return Param::DESCRIPTION;
    }

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate(string $value): void
    {
        assertMaxLenght($value, 255, Param::DESCRIPTION);
    }


}
