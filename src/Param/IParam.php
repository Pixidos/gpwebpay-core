<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

interface IParam
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return string
     */
    public function getParamName(): string;

    /**
     * @return mixed
     */
    public function getValue();
}
