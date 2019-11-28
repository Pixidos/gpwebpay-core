<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Param\IParam;

interface IRequest
{
    /**
     * Return all parameters
     * @return array
     */
    public function getParams(): array;

    /**
     * @param IParam $param
     */
    public function setParam(IParam $param): void;

    /**
     * Return only parameters what are included in digest
     * @return array
     */
    public function getDigestParams(): array;

    /**
     * Method only for ISinger
     *
     * @param string $digest
     *
     * @return void
     * @internal
     * @deprecated use setParam
     */
    public function setDigest(string $digest): void;

    /**
     * @param bool $asPost
     *
     * @return string
     */
    public function getRequestUrl(bool $asPost = false): string;

}
