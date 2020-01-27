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

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\Utils\DigestParamsFilter;
use Pixidos\GPWebPay\Param\Utils\Sorter;
use UnexpectedValueException;

class Request implements IRequest
{
    /**
     * @var  IOperation $operation
     */
    private $operation;
    /**
     * @var string|null $url
     */
    /**
     * @var array $params
     */
    private $params;
    /**
     * @var string
     */
    private $url;

    /**
     * @param IOperation     $operation
     * @param MerchantNumber $merchantNumber
     * @param DepositFlag    $depositFlag
     * @param string         $url
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function __construct(
        IOperation $operation,
        MerchantNumber $merchantNumber,
        DepositFlag $depositFlag,
        string $url
    ) {
        $this->operation = $operation;
        $this->url = $url;
        $this->setParam($merchantNumber);
        $this->setParam($depositFlag);

        $this->setParams();
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    public function sortParams(): void
    {
        $params = Sorter::sortRequestParams($this->params);
        $this->params = $params;
    }


    /**
     * @return array
     */
    public function getDigestParams(): array
    {
        $this->sortParams();

        return DigestParamsFilter::filter($this->params);
    }

    /**
     * @param IParam $param
     */
    public function setParam(IParam $param): void
    {
        $this->params[$param->getParamName()] = (string)$param;
    }

    public function getRequestUrl(bool $asPost = false): string
    {
        if ($asPost) {
            return $this->url;
        }

        return $this->url . '?' . http_build_query($this->getParams());
    }


    /**
     * Sets params to array
     */
    private function setParams(): void
    {
        foreach ($this->operation->getParams() as $param) {
            $this->setParam($param);
        }
    }
}
