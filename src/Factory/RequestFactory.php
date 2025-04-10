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
    public function __construct(
        private readonly PaymentConfigProvider $config,
        private readonly SignerProviderInterface $signerProvider
    ) {
    }

    /**
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
        $digestParams = $request->getDigestParams();
        $request->setParam(new Digest($signer->sign($digestParams)));
        $request->sortParams();

        return $request;
    }
}
