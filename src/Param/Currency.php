<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\Param;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class Currency implements IParam
{
    /**
     * @var CurrencyEnum
     */
    private $value;

    public function __construct(CurrencyEnum $currency)
    {
        $this->value = $currency;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }


    public function getParamName(): string
    {
        return Param::CURRENCY;
    }

    /**
     * @return CurrencyEnum
     */
    public function getValue(): CurrencyEnum
    {
        return $this->value;
    }

}
