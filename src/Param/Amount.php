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

class Amount implements IAmount
{
    /**
     * GP webpay AMOUNT is a numeric field of at most 15 digits in the smallest currency unit.
     * 999 999 999 999 999 < 2^50, so every allowed amount is an exact integer in a double.
     */
    private const MAX_VALUE = 999_999_999_999_999;
    private const MAX_DIGITS = 15;

    /**
     * Roundoff of a preceding calculation that is forgiven, see toSmallestUnit().
     */
    private const MAX_ROUNDOFF_ULPS = 2;
    private const MAX_ROUNDOFF_IN_SMALLEST_UNIT = 1e-6;

    private int $amount;

    /**
     * @deprecated use \Pixidos\GPWebPay\Param\AmountInPennies with an integer amount in the smallest
     *             currency unit (hellers, cents) instead. Floats cannot represent most decimal prices exactly.
     *
     * 19.99 becomes exactly 1999. The amount must be positive, may have at most two decimal places
     * when converted (none when $converToPennies is false) and at most 15 digits in the smallest unit.
     * Floating-point roundoff such as 0.1 + 0.2 is accepted only where it is far below one smallest unit.
     *
     * @param float $amount amount in the main currency unit, or in the smallest unit when $converToPennies is false
     * @param bool  $converToPennies
     *
     * @throws InvalidArgumentException
     */
    public function __construct(float $amount, bool $converToPennies = true)
    {
        $this->amount = self::toSmallestUnit($amount, $converToPennies ? 100 : 1);
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
     * A float price such as 19.99 is stored as the double nearest to 1999/100, which is slightly
     * different from 19.99. Formatting or casting that double loses information (the old (int)
     * cast produced 1998, rounding to 15 significant digits hid extra decimals), so the amount is
     * compared with doubles directly. It is accepted as N smallest units only when:
     *
     * 1. it is positive, finite and not above the double nearest to 999 999 999 999 999 / $scale.
     *    The range is checked first, so a larger value is never rounded down into the range.
     * 2. it is exactly the double nearest to N / $scale. A literal or a parsed decimal string with at
     *    most two decimals (none when $scale is 1) gives exactly this double; IEEE 754 division of the
     *    exact integers N and $scale gives it as well.
     * 3. or it differs from that double only by the roundoff of a preceding calculation: at most
     *    MAX_ROUNDOFF_ULPS representable steps (0.1 + 0.2 is one step above 0.3) and at most
     *    MAX_ROUNDOFF_IN_SMALLEST_UNIT. Two different decimals with at most 15 significant digits are
     *    more than 4 steps apart, so the step bound never absorbs a decimal the caller wrote. The
     *    absolute bound removes the allowance where steps are large: one step exceeds 1e-6 of the
     *    smallest unit from 2^26 main units (about 6.7e9 hellers) when converting and from 2^33
     *    smallest units (about 8.6e9) without conversion. Above that only exact doubles are accepted,
     *    so 1000000000000.001 and 999999999999998.5 are rejected.
     *
     * Decimals that round to the same double cannot be told apart. That happens only for values with
     * more than 15 significant digits, near the top of the range (e.g. 9999999999999.991). Longer
     * calculations, such as a sum of many prices, can exceed the forgiven roundoff; they are rejected,
     * never rounded to a different amount.
     *
     * @throws InvalidArgumentException
     */
    private static function toSmallestUnit(float $amount, int $scale): int
    {
        if (!is_finite($amount) || $amount <= 0.0) {
            throw self::notInteger($amount, $scale);
        }

        if ($amount > self::MAX_VALUE / (float)$scale) {
            throw new InvalidArgumentException(
                sprintf(
                    '%s max. length is %d! The amount exceeds %d in the smallest currency unit.',
                    Param::AMOUNT,
                    self::MAX_DIGITS,
                    self::MAX_VALUE
                )
            );
        }

        // Below the limit the product is less than 2^50 and within a fraction of a unit of N for every
        // amount accepted below, so rounding finds N. Acceptance itself does not rely on this rounding.
        $candidate = (int)round($amount * $scale);
        $nearest = $candidate / (float)$scale;

        if ($candidate < 1 || ($amount !== $nearest && !self::isRoundoff($amount, $nearest, $scale))) {
            throw self::notInteger($amount, $scale);
        }

        return $candidate;
    }

    private static function isRoundoff(float $amount, float $nearest, int $scale): bool
    {
        // both values are within a factor of two, so the subtraction is exact (Sterbenz lemma)
        return abs($amount - $nearest) * $scale <= self::MAX_ROUNDOFF_IN_SMALLEST_UNIT
            && self::ulpDistance($amount, $nearest) <= self::MAX_ROUNDOFF_ULPS;
    }

    /**
     * Number of representable doubles between two positive finite doubles: their IEEE 754 bit
     * patterns, read as integers, are ordered like the values and adjacent doubles differ by one.
     */
    private static function ulpDistance(float $first, float $second): int
    {
        $bits = unpack('q2', pack('d2', $first, $second));
        if (!is_array($bits) || !is_int($bits[1] ?? null) || !is_int($bits[2] ?? null)) {
            return PHP_INT_MAX; // @codeCoverageIgnore
        }

        return abs($bits[1] - $bits[2]);
    }

    private static function notInteger(float $amount, int $scale): InvalidArgumentException
    {
        $given = match (true) {
            is_nan($amount) => 'NAN', // casting NAN to string emits a warning since PHP 8.5
            is_infinite($amount) => $amount > 0 ? 'INF' : '-INF',
            default => (string)($amount * $scale),
        };

        return new InvalidArgumentException(sprintf('%s must be integer "%s" given.', Param::AMOUNT, $given));
    }
}
