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

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Param\IParam;

interface ResponseInterface
{
    public const PRCODE = 'PRCODE';
    public const SRCODE = 'SRCODE';
    public const RESULTTEXT = 'RESULTTEXT';
    public const EXPIRY = 'EXPIRY';
    public const ACSRES = 'ACSRES';
    public const ACCODE = 'ACCODE';
    public const DAYTOCAPTURE = 'DAYTOCAPTURE';
    public const TOKENREGSTATUS = 'TOKENREGSTATUS';
    public const ACRC = 'ACRC';
    public const RRN = 'RRN';
    public const PAR = 'PAR';
    public const TRACEID = 'TRACEID';
    public const DIGEST1 = 'DIGEST1';

    /**
     * Optional response-only fields that GP webpay includes in DIGEST and DIGEST1.
     * The signing order of all response fields is Sorter::RESPONSE_PARAM_ORDER.
     */
    public const RESPONSE_PARAMS = [
        self::EXPIRY,
        self::ACCODE,
        self::ACSRES,
        self::DAYTOCAPTURE,
        self::TOKENREGSTATUS,
        self::ACRC,
        self::RRN,
        self::PAR,
        self::TRACEID,
    ];

    /**
     * Signed response fields exactly as received, in signing order, without DIGEST and DIGEST1.
     *
     * @return array<string, IParam>
     */
    public function getParams(): array;

    /**
     * @return string
     */
    public function getDigest(): string;

    /**
     * @return bool
     */
    public function hasError(): bool;

    /**
     * @return string
     */
    public function getDigest1(): string;

    /**
     * MERORDERNUM as received: null when it was not received, an empty string when it was received empty.
     *
     * @return string|null
     */
    public function getMerOrderNumber(): ?string;

    /**
     * Merchant data the caller added to the request with the Md parameter,
     * without the gateway routing prefix and without the "#ID=…" or "#IDPP=…" suffix
     * that GP webpay may append. Null when the request carried no merchant data.
     * The exact signed MD value is available in getParams().
     *
     * @return string|null
     */
    public function getMd(): ?string;

    /**
     * @return string
     */
    public function getGatewayKey(): string;

    /**
     * @return string
     */
    public function getOrderNumber(): string;

    /**
     * @return int
     */
    public function getSrcode(): int;

    /**
     * @return int
     */
    public function getPrcode(): int;

    /**
     * @return string
     */
    public function getResultText(): string;

    /**
     * Legacy response field. Since HTTP API 1.17 GP webpay returns the card token in TOKEN instead.
     *
     * @return string|null
     */
    public function getUserParam1(): ?string;

    /**
     * Sorting params order by documentation
     */
    public function sortParams(): void;
}
