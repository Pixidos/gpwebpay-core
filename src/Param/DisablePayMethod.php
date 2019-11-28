<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class DisablePayMethod implements IParam
{
    /**
     * @var PayMethodEnum
     */
    private $value;

    /**
     * DisablePayMethod constructor.
     *
     * @param PayMethodEnum $method
     */
    public function __construct(PayMethodEnum $method)
    {
        $this->value = $method;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }


    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::DISABLEPAYMETHOD;
    }

    /**
     * @return PayMethodEnum
     */
    public function getValue(): PayMethodEnum
    {
        return $this->value;
    }

}
