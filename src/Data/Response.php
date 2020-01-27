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

use Pixidos\GPWebPay\Enum\Operation as EnumOperation;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\MerOrderNum;
use Pixidos\GPWebPay\Param\Operation;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\ResponseParam;
use Pixidos\GPWebPay\Param\UserParam;
use Pixidos\GPWebPay\Param\Utils\Sorter;

class Response implements IResponse
{

    /**
     * @var IParam[] $params
     */
    private $params;
    /**
     * @var string digest
     */
    private $digest;
    /**
     * @var string digest1
     */
    private $digest1;
    /**
     * @var string gatewayKey
     */
    private $gatewayKey;

    /**
     * @param string      $operation
     * @param string      $ordernumber
     * @param string|null $merordernum
     * @param string      $md
     * @param int         $prcode
     * @param int         $srcode
     * @param string      $resulttext
     * @param string      $digest
     * @param string      $digest1
     * @param string      $gatewayKey
     */
    public function __construct(
        string $operation,
        string $ordernumber,
        ?string $merordernum,
        ?string $md,
        int $prcode,
        int $srcode,
        ?string $resulttext,
        string $digest,
        string $digest1,
        string $gatewayKey
    ) {
        $this->addParam(
            new Operation(EnumOperation::fromScalar($operation))
        );
        $this->addParam(new OrderNumber($ordernumber));

        if ($merordernum !== null) {
            $this->addParam(new MerOrderNum($merordernum));
        }

        if ($md !== null) {
            $this->addParam(new Md($md));
        }
        $this->addParam(new ResponseParam((string)$prcode, self::PRCODE));
        $this->addParam(new ResponseParam((string)$srcode, self::SRCODE));


        if ($resulttext !== null) {
            $this->addParam(new ResponseParam($resulttext, self::RESULTTEXT));
        }

        $this->digest = $digest;
        $this->digest1 = $digest1;
        $this->gatewayKey = $gatewayKey;
    }


    /**
     * @return string
     */
    public function getDigest(): string
    {
        return $this->digest;
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return (bool)$this->params[self::PRCODE]->getValue()
            || (bool)$this->params[self::SRCODE]->getValue();
    }

    /**
     * @return string
     */
    public function getDigest1(): string
    {
        return $this->digest1;
    }

    /**
     * @return string|null
     */
    public function getMerOrderNumber(): ?string
    {
        return isset($this->params[Param::MERORDERNUM]) ? (string)$this->params[Param::MERORDERNUM] : null;
    }

    /**
     * @return string|null
     */
    public function getMd(): ?string
    {
        if (!isset($this->params[Param::MD])) {
            return null;
        }
        $explode = explode('|', $this->params[Param::MD]->getValue(), 2);

        return $explode[1] ?? null;
    }

    /**
     * @return string
     */
    public function getGatewayKey(): string
    {
        return $this->gatewayKey;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->params[Param::ORDERNUMBER]->getValue();
    }

    /**
     * @return int
     */
    public function getSrcode(): int
    {
        return (int)$this->params[self::SRCODE]->getValue();
    }

    /**
     * @return int
     */
    public function getPrcode(): int
    {
        return (int)$this->params[self::PRCODE]->getValue();
    }

    /**
     * @return string|null
     */
    public function getResultText(): ?string
    {
        return isset($this->params[self::RESULTTEXT]) ? $this->params[self::RESULTTEXT]->getValue() : null;
    }

    /**
     * @return string|null
     */
    public function getUserParam1(): ?string
    {
        return isset($this->params[Param::USERPARAM]) ? $this->params[Param::USERPARAM]->getValue() : null;
    }

    /**
     * @param string $userParam1
     * @deprecated use addParam()
     */
    public function setUserParam1(string $userParam1): void
    {
        $this->addParam(new UserParam($userParam1));
    }

    public function addParam(IParam $param): void
    {
        $this->params[$param->getParamName()] = $param;
    }

    public function getParam(string $paramName): ?IParam
    {
        return $this->params[$paramName] ?? null;
    }

    /**
     * @return array<IParam>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function sortParams(): void
    {
        $this->params = Sorter::sortResponseParams($this->params);
    }
}
