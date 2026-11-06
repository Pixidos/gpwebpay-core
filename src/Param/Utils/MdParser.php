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

/**
 * Reads the MD value of a response.
 *
 * Requests created by Operation carry MD in one of these forms:
 *   "<gateway>"            gateway selected, no merchant data
 *   "<gateway>|<payload>"  gateway selected, merchant data added with the Md parameter
 *   "|<payload>"           default gateway, merchant data added with the Md parameter
 * GP webpay returns MD unchanged, but may append "#ID=…" (Integration Advice Switch)
 * or "#IDPP=…" (PayPal) to it. Signatures always cover the complete received value;
 * this class only interprets it.
 */
final class MdParser
{
    public const GATEWAY_SEPARATOR = '|';

    private const PROVIDER_SUFFIX_PATTERN = '~(?:#ID(?:PP)?=[^#|]*)+$~D';

    /**
     * Gateway key at the start of MD (lowercase), or an empty string when MD does not select a gateway.
     */
    public static function getGatewayKey(string $md): string
    {
        return strtolower(substr($md, 0, strcspn($md, self::GATEWAY_SEPARATOR . '#')));
    }

    /**
     * Merchant data after the gateway separator, without the suffix appended by GP webpay.
     * Null when MD contains no merchant data.
     */
    public static function getPayload(string $md): ?string
    {
        $position = strpos($md, self::GATEWAY_SEPARATOR);
        if (false === $position) {
            return null;
        }

        return (string)preg_replace(self::PROVIDER_SUFFIX_PATTERN, '', substr($md, $position + 1));
    }
}
