<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
use Pixidos\GPWebPay\Enum\Param;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class Operation implements IParam
{
    /**
     * @var OperationEnum
     */
    private $value;

    /**
     * Operation constructor.
     *
     * @param OperationEnum $operation
     */
    public function __construct(OperationEnum $operation)
    {
        $this->value = $operation;
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
        return Param::OPERATION;
    }

    /**
     * @return OperationEnum
     */
    public function getValue(): OperationEnum
    {
        return $this->value;
    }

}
