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

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

use function Pixidos\GPWebPay\assertMaxLength;

class Amount implements IAmount
{
    /**
     * GP webpay AMOUNT is a numeric field of at most 15 digits in the smallest currency unit.
     */
    private const MAX_DIGITS = 15;

    private int $amount;

    /**
     * @deprecated use \Pixidos\GPWebPay\Param\AmountInPennies with an integer amount in the smallest
     *             currency unit (hellers, cents) instead. Floats cannot represent most decimal prices exactly.
     *
     * The value is converted from its shortest decimal representation (up to 15 significant digits),
     * so 19.99 becomes exactly 1999. The amount must be positive, may have at most two decimal places
     * when converted (none when $converToPennies is false) and at most 15 digits in the smallest unit.
     *
     * @param float $amount amount in the main currency unit, or in the smallest unit when $converToPennies is false
     * @param bool  $converToPennies
     *
     * @throws InvalidArgumentException
     */
    public function __construct(float $amount, bool $converToPennies = true)
    {
        $this->amount = self::toSmallestUnit($amount, $converToPennies ? 2 : 0);
    }


    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::AMOUNT;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->amount;
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->amount;
    }

    /**
     * @throws InvalidArgumentException
     */
    private static function toSmallestUnit(float $amount, int $decimals): int
    {
        // %H prints at most 15 significant digits without locale-specific separators. Any decimal number
        // with up to 15 significant digits survives the round trip through a double, so this recovers
        // the decimal value the caller wrote (19.99) instead of its binary approximation (19.989999…).
        $decimal = is_finite($amount) ? sprintf('%.15H', $amount) : '';

        if (1 !== preg_match('#^(\d+)(?:\.(\d+))?$#D', $decimal, $matches)) {
            if (is_finite($amount) && $amount * 10 ** $decimals >= 10 ** self::MAX_DIGITS) {
                // only reached for values printed in exponent notation
                assertMaxLength(sprintf('%.0F', $amount * 10 ** $decimals), self::MAX_DIGITS, Param::AMOUNT);
            }

            throw self::notInteger($amount, $decimals);
        }

        $fraction = $matches[2] ?? '';
        if (strlen($fraction) > $decimals) {
            throw self::notInteger($amount, $decimals);
        }

        $digits = ltrim($matches[1] . str_pad($fraction, $decimals, '0'), '0');
        if ('' === $digits) {
            throw self::notInteger($amount, $decimals);
        }

        assertMaxLength($digits, self::MAX_DIGITS, Param::AMOUNT);

        return (int)$digits;
    }

    private static function notInteger(float $amount, int $decimals): InvalidArgumentException
    {
        $given = match (true) {
            is_nan($amount) => 'NAN', // casting NAN to string emits a warning since PHP 8.5
            is_infinite($amount) => $amount > 0 ? 'INF' : '-INF',
            default => (string)($amount * 10 ** $decimals),
        };

        return new InvalidArgumentException(sprintf('%s must be integer "%s" given.', Param::AMOUNT, $given));
    }
}
