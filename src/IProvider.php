<?php declare(strict_types=1);

namespace Pixidos\GPWebPay;

use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Data\IOperation;
use Pixidos\GPWebPay\Data\IRequest;
use Pixidos\GPWebPay\Data\IResponse;

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
