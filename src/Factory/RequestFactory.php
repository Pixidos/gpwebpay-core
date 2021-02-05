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

namespace Pixidos\GPWebPay\Factory;

use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Data\OperationInterface;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\LogicException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Param\Digest;
use Pixidos\GPWebPay\Signer\SignerProviderInterface;
use UnexpectedValueException;

class RequestFactory
{
    /**
     * @var PaymentConfigProvider
     */
    private $config;
    /**
     * @var SignerProviderInterface
     */
    private $signerProvider;

    /**
     * RequestFactory constructor.
     * @param PaymentConfigProvider   $config
     * @param SignerProviderInterface $signerProvider
     */
    public function __construct(PaymentConfigProvider $config, SignerProviderInterface $signerProvider)
    {
        $this->config = $config;
        $this->signerProvider = $signerProvider;
    }

    /**
     * @param OperationInterface $operation
     *
     * @return Request
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws UnexpectedValueException
     */
    public function create(OperationInterface $operation): Request
    {
        $key = $this->config->getGateway($operation->getGateway());
        if (null === $operation->getParam(Param::RESPONSE_URL())) {
            $responseUrl = $this->config->getResponseUrl();
            if (null === $responseUrl) {
                throw new LogicException('You are forgot setup response url');
            }
            $operation->addParam($responseUrl);
        }
        $request = new  Request(
            $operation,
            $this->config->getMerchantNumber($key),
            $this->config->getDepositFlag($key),
            $this->config->getUrl($key)
        );

        $signer = $this->signerProvider->get($key);
        $request->setParam(new Digest($signer->sign($request->getDigestParams())));
        $request->sortParams();

        return $request;
    }
}
