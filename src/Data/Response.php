<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Enum\Param;

/**
 * Class Response
 * @package Pixidos\GPWebPay
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
class Response implements IResponse
{

    /**
     * @var array $params
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
        $this->params[Param::OPERATION] = $operation;
        $this->params[Param::ORDERNUMBER] = $ordernumber;

        if ($merordernum !== null) {
            $this->params[Param::MERORDERNUM] = $merordernum;
        }

        if ($md !== null) {
            $this->params[Param::MD] = $md;
        }

        $this->params[self::PRCODE] = $prcode;
        $this->params[self::SRCODE] = $srcode;

        if ($resulttext !== null) {
            $this->params[self::RESULTTEXT] = $resulttext;
        }

        $this->digest = $digest;
        $this->digest1 = $digest1;
        $this->gatewayKey = $gatewayKey;
    }


    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
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
        return (bool)$this->params[self::PRCODE] || (bool)$this->params[self::SRCODE];
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
        return $this->params[Param::MERORDERNUM] ?? null;
    }

    /**
     * @return string|null
     */
    public function getMd(): ?string
    {
        $explode = explode('|', $this->params[Param::MD], 2);

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
        return $this->params[Param::ORDERNUMBER];
    }

    /**
     * @return int
     */
    public function getSrcode(): int
    {
        return $this->params[self::SRCODE];
    }

    /**
     * @return int
     */
    public function getPrcode(): int
    {
        return $this->params[self::PRCODE];
    }

    /**
     * @return string|null
     */
    public function getResultText(): ?string
    {
        return $this->params[self::RESULTTEXT] ?? null;
    }

    /**
     * @return string|null
     */
    public function getUserParam1(): ?string
    {
        return $this->params[Param::USERPARAM] ?? null;
    }

    /**
     * @param string $userParam1
     */
    public function setUserParam1(string $userParam1): void
    {
        $this->params[Param::USERPARAM] = $userParam1;
    }


}
