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

namespace Pixidos\GPWebPay\Factory;

use Pixidos\GPWebPay\Data\IOperation;
use Pixidos\GPWebPay\Data\Request;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Param\Digest;
use Pixidos\GPWebPay\Settings\Settings;
use Pixidos\GPWebPay\Signer\ISignerFactory;
use UnexpectedValueException;

class RequestFactory
{
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var ISignerFactory
     */
    private $signerFactory;

    public function __construct(Settings $settings, ISignerFactory $signerFactory)
    {
        $this->settings = $settings;
        $this->signerFactory = $signerFactory;
    }

    /**
     * @param IOperation $operation
     *
     * @return Request
     * @throws InvalidArgumentException
     * @throws SignerException
     * @throws UnexpectedValueException
     */
    public function create(IOperation $operation): Request
    {
        $key = $this->settings->getGatewayKey($operation->getGatewayKey());
        $request = new  Request(
            $operation,
            $this->settings->getMerchantNumber($key),
            $this->settings->getDepositFlag($key),
            $this->settings->getUrl($key)
        );

        $signer = $this->signerFactory->create($operation->getGatewayKey());
        $request->setParam(new Digest($signer->sign($request->getDigestParams())));

        return $request;
    }
}
