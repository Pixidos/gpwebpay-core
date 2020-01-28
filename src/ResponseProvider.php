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
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\SignerProvider;

class ResponseProvider implements ResponseProviderInterface
{
    /**
     * @var array<callable>
     */
    public $onSuccess = [];

    /**
     * @var array<callable>
     */
    public $onError = [];

    /**
     * @var SignerProvider
     */
    private $signerProvider;

    /**
     * @var PaymentConfigProvider settings
     */
    private $settings;

    /**
     * Provider constructor.
     *
     * @param PaymentConfigProvider $configProvider
     * @param SignerProvider        $signerProvider
     */
    public function __construct(
        PaymentConfigProvider $configProvider,
        SignerProvider $signerProvider
    ) {
        $this->signerProvider = $signerProvider;
        $this->settings = $configProvider;
    }


    public function provide(IResponse $response): IResponse
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
     * @param IResponse $response
     *
     * @return bool
     * @throws GPWebPayException
     * @throws GPWebPayResultException
     */
    public function verifyPaymentResponse(IResponse $response): bool
    {
        // verify digest & digest1
        $signer = $this->signerProvider->get($response->getGatewayKey());

        $params = $response->getParams();
        $verify = $signer->verify($params, $response->getDigest());
        $params[Param::MERCHANTNUMBER] = $this->settings->getMerchantNumber($response->getGatewayKey());
        $verify1 = $signer->verify($params, $response->getDigest1());

        return !($verify === false || $verify1 === false);
    }

    public function addOnSuccess(Closure $closure): self
    {
        $this->onSuccess[] = $closure;

        return $this;
    }

    public function addOnError(Closure $closure): self
    {
        $this->onError[] = $closure;

        return $this;
    }

    private function onSuccess(IResponse $response): void
    {
        foreach ($this->onSuccess as $callback) {
            $callback($response);
        }
    }

    /**
     * @param GPWebPayException $exception
     * @param IResponse         $response
     */
    private function onError(GPWebPayException $exception, IResponse $response): void
    {
        if (count($this->onError) === 0) {
            throw $exception;
        }

        foreach ($this->onError as $callback) {
            $callback($exception, $response);
        }
    }

}
