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

namespace Pixidos\GPWebPay;

use Closure;
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\SignerProviderInterface;

class ResponseProvider implements ResponseProviderInterface
{
    /**
     * @var array<callable>
     */
    public array $onSuccess = [];

    /**
     * @var array<callable>
     */
    public array $onError = [];


    public function __construct(
        private readonly PaymentConfigProvider $configProvider,
        private readonly SignerProviderInterface $signerProvider
    ) {
    }


    public function provide(ResponseInterface $response): ResponseInterface
    {
        try {
            if (!$this->verifyPaymentResponse($response)) {
                throw new SignerException('Digest or Digest1 is incorrect!');
            }

            // verify PRCODE and SRCODE
            if ($response->hasError()) {
                throw new GPWebPayResultException(
                    'Response has an error.',
                    $response->getPrcode(),
                    $response->getSrcode(),
                    $response->getResultText()
                );
            }

            $this->onSuccess($response);
        } catch (GPWebPayException $exception) {
            $this->onError($exception, $response);
        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     * @throws GPWebPayException
     * @throws GPWebPayResultException
     */
    public function verifyPaymentResponse(ResponseInterface $response): bool
    {
        // verify digest & digest1
        $signer = $this->signerProvider->get($response->getGatewayKey());

        $params = $response->getParams();
        $verify = $signer->verify($params, $response->getDigest());
        $params[Param::MERCHANTNUMBER] = $this->configProvider->getMerchantNumber($response->getGatewayKey());
        $verify1 = $signer->verify($params, $response->getDigest1());

        return !(false === $verify || false === $verify1);
    }

    public function addOnSuccess(Closure $closure): ResponseProviderInterface
    {
        $this->onSuccess[] = $closure;

        return $this;
    }

    public function addOnError(Closure $closure): ResponseProviderInterface
    {
        $this->onError[] = $closure;

        return $this;
    }

    private function onSuccess(ResponseInterface $response): void
    {
        foreach ($this->onSuccess as $callback) {
            $callback($response);
        }
    }

    /**
     * @param GPWebPayException $exception
     * @param ResponseInterface $response
     */
    private function onError(GPWebPayException $exception, ResponseInterface $response): void
    {
        if (0 === count($this->onError)) {
            throw $exception;
        }

        foreach ($this->onError as $callback) {
            $callback($exception, $response);
        }
    }
}
