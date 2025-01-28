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

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\IParam;

interface OperationInterface
{
    public function getGateway(): ?string;

    /**
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): OperationInterface;

    public function getParam(Param $param): ?IParam;

    /**
     * @return array<IParam>
     */
    public function getParams(): array;
}
