<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
use Pixidos\GPWebPay\Enum\Param;

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
