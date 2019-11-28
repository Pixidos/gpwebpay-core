<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\DepositFlag as DepositFlagEnum;
use Pixidos\GPWebPay\Enum\Param;

class DepositFlag implements IParam
{
    /**
     * @var DepositFlagEnum
     */
    private $value;

    /**
     * DepositFlag constructor.
     *
     * @param DepositFlagEnum $currency
     */
    public function __construct(DepositFlagEnum $currency)
    {
        $this->value = $currency;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }

    /**
     * @return DepositFlagEnum
     */
    public function getValue(): DepositFlagEnum
    {
        return $this->value;
    }


    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::DEPOSITFLAG;
    }
}
