<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay;

use Pixidos\GPWebPay\Data\IOperation;
use Pixidos\GPWebPay\Data\IRequest;
use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\GPWebPayException;
use Pixidos\GPWebPay\Exceptions\GPWebPayResultException;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Factory\RequestFactory;
use Pixidos\GPWebPay\Factory\ResponseFactory;
use Pixidos\GPWebPay\Settings\Settings;
use Pixidos\GPWebPay\Signer\ISignerFactory;
use UnexpectedValueException;

class Provider implements IProvider
{

    /**
     * @var ISignerFactory signerFactory
     */
    private $signerFactory;

    /**
     * @var Settings settings
     */
    private $settings;
    /**
     * @var RequestFactory
     */
    private $requestFactory;
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * Provider constructor.
     *
     * @param Settings $settings
     * @param RequestFactory $requestFactory
     * @param ISignerFactory $signerFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Settings $settings, ISignerFactory $signerFactory, RequestFactory $requestFactory, ResponseFactory $responseFactory)
    {
        $this->signerFactory = $signerFactory;
        $this->settings = $settings;
        $this->requestFactory = $requestFactory;
        $this->responseFactory = $responseFactory;
    }


    /**
     * @param IOperation $operation
     *
     * @return IRequest
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws UnexpectedValueException
     */
    public function createRequest(IOperation $operation): IRequest
    {
        return $this->requestFactory->create($operation);
    }


    /**
     * @param IRequest $request
     * @param bool $asPost
     *
     * @return string
     */
    public function getRequestUrl(IRequest $request, bool $asPost = false): string
    {
        return $request->getRequestUrl($asPost);
    }


    /**
     * @param array $params
     *
     * @return IResponse
     */
    public function createResponse(array $params): IResponse
    {
        return $this->responseFactory->create($params);
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
        $signer = $this->signerFactory->create($response->getGatewayKey());

        $params = $response->getParams();
        $verify = $signer->verify($params, $response->getDigest());
        $params[Param::MERCHANTNUMBER] = $this->settings->getMerchantNumber($response->getGatewayKey());
        $verify1 = $signer->verify($params, $response->getDigest1());
        if (!$verify || $verify1) {
            throw new GPWebPayException('Digest or Digest1 is incorrect!');
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

        return true;
    }

}
