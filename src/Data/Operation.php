<?php

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

declare(strict_types=1);

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Enum\Operation as OperationEnum;
use Pixidos\GPWebPay\Enum\Param as ParamEnum;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\IAmount;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\Operation as OperationParam;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;

/**
 * Class Operation
 * @package Pixidos\GPWebPay
 * @author  Ondra Votava <ondra@votava.dev>
 */
class Operation implements OperationInterface
{
    private ?string $gateway = null;

    /**
     * @var IParam[]
     */
    private array $params = [];


    public function __construct(
        OrderNumber $orderNumber,
        IAmount $amount,
        Currency $currency,
        ?string $gateway = null,
        ?ResponseUrl $responseUrl = null,
        ?OperationEnum $operation = null
    ) {
        if ($operation === null) {
            $operation = OperationEnum::CREATE_ORDER();
        }
        $this->addParam(new OperationParam($operation));
        $this->addParam($amount);
        $this->addParam($orderNumber);
        $this->addParam($currency);

        if (null !== $gateway) {
            $gateway = strtolower($gateway);
            $this->gateway = $gateway;
            $this->addParam(new Md($gateway));
        }

        if (null !== $responseUrl) {
            $this->addParam($responseUrl);
        }
    }

    public function getGateway(): ?string
    {
        return $this->gateway;
    }

    /**
     * @param IParam $param
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): OperationInterface
    {
        if (($param instanceof Md) && $this->gateway !== (string)$param) {
            $param = new Md($this->gateway . '|' . $param);
        }

        $this->params[$param->getParamName()] = $param;

        return $this;
    }

    /**
     * @param ParamEnum $param
     *
     * @return IParam|null
     */
    public function getParam(ParamEnum $param): ?IParam
    {
        return $this->params[(string)$param] ?? null;
    }

    /**
     * @return IParam[]
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
