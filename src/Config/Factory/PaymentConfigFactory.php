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

namespace Pixidos\GPWebPay\Config\Factory;

use Pixidos\GPWebPay\Config\PaymentConfig;
use Pixidos\GPWebPay\Enum\DepositFlag as DepositFlagEnum;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;

class PaymentConfigFactory
{
    /**
     * @param string $url
     * @param string $merchantNumber
     * @param int    $depositFlag
     * @param string $gateway
     * @param string $responseUrl
     * @return PaymentConfig
     */
    public function create(
        string $url,
        string $merchantNumber,
        int $depositFlag,
        string $gateway,
        string $responseUrl
    ): PaymentConfig {
        $target = '' !== $responseUrl ? new ResponseUrl($responseUrl) : null;

        return new PaymentConfig(
            $url,
            new MerchantNumber($merchantNumber),
            new DepositFlag(DepositFlagEnum::fromScalar($depositFlag)),
            $gateway,
            $target
        );
    }
}
