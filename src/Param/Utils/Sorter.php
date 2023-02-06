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

class Sorter
{
    public const REQUEST_PARAM_ORDER = [
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
        Param::USERPARAM,
        Param::VRCODE,
        Param::FASTPAYID,
        Param::PAYMETHOD,
        Param::DISABLEPAYMETHOD,
        Param::PAYMETHODS,
        Param::EMAIL,
        Param::REFERENCENUMBER,
        Param::ADDINFO,
        Param::PANPATTERN,
        Param::TOKEN,
        Param::FAST_TOKEN,
        Param::DIGEST,
        Param::LANG,
    ];

    public const RESPONSE_PARAM_ORDER = [
        Param::OPERATION,
        Param::ORDERNUMBER,
        Param::MERORDERNUM,
        Param::MD,
        ResponseInterface::PRCODE,
        ResponseInterface::SRCODE,
        ResponseInterface::RESULTTEXT,
        Param::USERPARAM,
        Param::ADDINFO,
        Param::TOKEN,
        ResponseInterface::EXPIRY,
        ResponseInterface::ACSRES,
        ResponseInterface::ACCODE,
        Param::PANPATTERN,
        ResponseInterface::DAYTOCAPTURE,
        ResponseInterface::TOKENREGSTATUS,
        Param::DIGEST,
        'DIGEST1',
    ];


    /**
     * @template T
     * @param array<string, T> $params
     * @return array<string, T>
     */
    public static function sortRequestParams(array $params): array
    {
        $order = array_flip(self::REQUEST_PARAM_ORDER);

        return self::sort($params, $order);
    }

    /**
     * @template T
     * @param array<string, T> $params
     * @return array<string, T>
     */
    public static function sortResponseParams(array $params): array
    {
        $order = array_flip(self::RESPONSE_PARAM_ORDER);

        return self::sort($params, $order);
    }

    /**
     * @template T
     * @param array<string, T> $params
     * @param array<string, int>    $order
     * @return array<string, T>
     */
    private static function sort(array $params, array $order): array
    {
        $sort = array_replace($order, $params);

        /**
         * @phpstan-ignore-next-line
         */
        return array_intersect_key($sort, $params);
    }
}
