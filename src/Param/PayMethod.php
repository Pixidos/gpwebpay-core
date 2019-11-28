<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class PayMethod implements IParam
{
    /**
     * @var PayMethodEnum
     */
    private $value;

    /**
     * PayMethod constructor.
     *
     * @param PayMethodEnum $method
     */
    public function __construct(PayMethodEnum $method)
    {
        $this->value = $method;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }


    public function getParamName(): string
    {
        return Param::PAYMETHOD;
    }

    /**
     * @return PayMethodEnum
     */
    public function getValue(): PayMethodEnum
    {
        return $this->value;
    }

}
