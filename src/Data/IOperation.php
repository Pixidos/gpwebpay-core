<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\IParam;

/**
 * Class OperationContract
 * @package Pixidos\GPWebPay\Contracts
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
interface IOperation
{
    /**
     * @return null|string
     */
    public function getGatewayKey(): ?string;

    /**
     * @param IParam $param
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): IOperation;

    /**
     * @param Param $param
     *
     * @return IParam|null
     */
    public function getParam(Param $param): ?IParam;

    /**
     * @return IParam[]
     */
    public function getParams(): array;
}
