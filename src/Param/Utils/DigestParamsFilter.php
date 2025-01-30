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

namespace Pixidos\GPWebPay\Param\Utils;

use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;

final class DigestParamsFilter
{
    /**
     * @var string[] DIGEST_PARAMS_KEYS
     */
    public const DIGEST_PARAMS_KEYS = [
            Param::MERCHANTNUMBER,
            Param::OPERATION,
            Param::ORDERNUMBER,
            Param::AMOUNT,
            Param::CURRENCY,
            Param::DEPOSITFLAG,
            Param::MERORDERNUM,
            Param::RESPONSE_URL,
            Param::DESCRIPTION,
            Param::MD,
            Param::PAYMETHOD,
            Param::DISABLEPAYMETHOD,
            Param::PAYMETHODS,
            Param::EMAIL,
            Param::REFERENCENUMBER,
            Param::ADDINFO,
            Param::TOKEN,
            Param::FAST_TOKEN,
            Param::USERPARAM,
            Param::FASTPAYID,
            ResponseInterface::RESULTTEXT,
            ResponseInterface::SRCODE,
            ResponseInterface::PRCODE,
            ResponseInterface::EXPIRY,
            ResponseInterface::ACSRES,
            ResponseInterface::ACCODE,
            ResponseInterface::DAYTOCAPTURE,
            ResponseInterface::TOKENREGSTATUS,
    ];

    /**
     * @param array<string, string> $params
     * @return array<string, string>
     */
    public static function filter(array $params): array
    {
        return array_intersect_key($params, array_flip(self::DIGEST_PARAMS_KEYS));
    }
}
