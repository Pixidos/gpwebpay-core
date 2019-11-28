<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class PayMethods implements IParam
{
    /**
     * @var PayMethodEnum[]
     */
    private $methods;
    /**
     * @var string
     */
    private $string;


    /**
     * PayMethods constructor.
     *
     * @param PayMethodEnum ...$methods
     */
    public function __construct(PayMethodEnum ...$methods)
    {
        $this->methods = array_unique($methods);
        $this->createString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->string;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::PAYMETHODS;
    }

    /**
     * @return PayMethodEnum[]
     */
    public function getValue(): array
    {
        return $this->methods;
    }

    /**
     * @param PayMethodEnum $method
     */
    public function addMethod(PayMethodEnum $method): void
    {
        $this->methods[] = $method;
        $this->methods = array_unique($this->methods);
        $this->createString();
    }

    private function createString(): void
    {
        $string = implode(',', $this->methods);
        $this->string = $string;
    }

}
