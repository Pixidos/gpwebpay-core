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
use Pixidos\GPWebPay\Param\Utils\MdParser;

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
            // gateway routing marker only; merchant data added later get the "<gateway>|" prefix
            $this->params[ParamEnum::MD] = new Md($gateway);
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
     * An Md parameter carries merchant data. It is sent as "<gateway>|<data>"
     * (or "|<data>" without a gateway), so the response can be routed to the right gateway
     * and ResponseInterface::getMd() returns exactly <data>, even when it equals the gateway name.
     *
     * @param IParam $param
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): OperationInterface
    {
        if ($param instanceof Md) {
            $param = new Md($this->gateway . MdParser::GATEWAY_SEPARATOR . $param);
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
