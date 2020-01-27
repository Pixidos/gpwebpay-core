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

namespace Pixidos\GPWebPay;

use Pixidos\GPWebPay\Data\IOperation;
use Pixidos\GPWebPay\Data\IRequest;
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;

interface IProvider
{
    /**
     * @param IOperation $operation
     *
     * @return IRequest
     */
    public function createRequest(IOperation $operation): IRequest;


    /**
     * @param array $params
     *
     * @return IResponse
     */
    public function createResponse(array $params): IResponse;

    /**
     * @param IResponse $response
     *
     * @return bool
     * @throws GPWebPayException
     * @throws GPWebPayResultException
     */
    public function verifyPaymentResponse(IResponse $response): bool;
}
