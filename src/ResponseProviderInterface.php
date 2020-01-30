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

use Closure;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;

interface ResponseProviderInterface
{
    /**
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function provide(ResponseInterface $response): ResponseInterface;


    /**
     * @param ResponseInterface $response
     *
     * @return bool
     * @throws GPWebPayException
     * @throws GPWebPayResultException
     */
    public function verifyPaymentResponse(ResponseInterface $response): bool;

    /**
     * @param Closure $closure signature: function(GPWebPayException $exception, ResponseInterface $response)
     * @return $this
     */
    public function addOnError(Closure $closure): self;

    /**
     * @param Closure $closure signature: function(ResponseInterface $response)
     * @return $this
     */
    public function addOnSuccess(Closure $closure): self;

}
