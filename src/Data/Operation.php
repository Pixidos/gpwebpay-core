<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Enum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
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
class Operation implements IOperation
{
    /**
     * @var string|null $gatewayKey
     */
    private $gatewayKey;

    /**
     * @var IParam[]
     */
    private $params = [];


    /**
     * Operation constructor.
     * @param OrderNumber      $orderNumber
     * @param Amount           $amount
     * @param Currency         $currency
     * @param string|null      $gatewayKey
     * @param ResponseUrl|null $responseUrl
     */
    public function __construct(
        OrderNumber $orderNumber,
        Amount $amount,
        Currency $currency,
        ?string $gatewayKey = null,
        ?ResponseUrl $responseUrl = null
    ) {
        $this->addParam(new OperationParam(Enum\Operation::CREATE_ORDER()));
        $this->addParam($amount);
        $this->addParam($orderNumber);
        $this->addParam($currency);

        if ($gatewayKey !== null) {
            $gatewayKey = strtolower($gatewayKey);
            $this->gatewayKey = $gatewayKey;
            $this->addParam(new Md($gatewayKey));
        }

        if ($responseUrl !== null) {
            $this->addParam($responseUrl);
        }
    }

    /**
     * @return null|string
     */
    public function getGatewayKey(): ?string
    {
        return $this->gatewayKey;
    }

    /**
     * @param IParam $param
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): IOperation
    {
        if (($param instanceof Md) && $this->gatewayKey !== (string)$param) {
            $param = new Md($this->gatewayKey . '|' . $param);
        }

        $this->params[$param->getParamName()] = $param;

        return $this;
    }

    /**
     * @param Param $param
     *
     * @return IParam|null
     */
    public function getParam(Param $param): ?IParam
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
