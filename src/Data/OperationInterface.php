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

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\IParam;

interface OperationInterface
{
    /**
     * @return null|string
     */
    public function getGateway(): ?string;

    /**
     * @param IParam $param
     *
     * @return OperationInterface
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): OperationInterface;

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
