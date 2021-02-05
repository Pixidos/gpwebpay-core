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

namespace Pixidos\GPWebPay;

use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

/**
 * @param mixed  $value
 * @param string $name
 *
 * @throws InvalidArgumentException
 */
function assertIsInteger($value, string $name): void
{
    if (!is_numeric($value)) {
        throw new InvalidArgumentException(
            sprintf('%s must be numeric scalar type "%s" given.', $name, gettype($value))
        );
    }

    if (false === (bool)preg_match('#^[1-9]\d*$#', (string)$value)) {
        throw new InvalidArgumentException(sprintf('%s must be integer "%s" given.', $name, $value));
    }
}

/**
 * @param mixed  $value
 * @param int    $length
 * @param string $name
 *
 * @throws InvalidArgumentException
 */
function assertMaxLenght($value, int $length, string $name): void
{
    $strlen = strlen((string)$value);
    if ($strlen > $length) {
        throw new InvalidArgumentException(sprintf('%s max. length is %s! "%s" given.', $name, $length, $strlen));
    }
}

/**
 * @param mixed  $value
 * @param int    $length
 * @param string $name
 *
 * @throws InvalidArgumentException
 */
function assertLenght($value, int $length, string $name): void
{
    $strlen = strlen((string)$value);
    if ($strlen !== $length) {
        throw new InvalidArgumentException(sprintf('%s max. length is %s! "%s" given.', $name, $length, $strlen));
    }
}

/**
 * @param string $value
 *
 * @throws InvalidArgumentException
 */
function assertIsEmail(string $value): void
{
    $atom = "[-a-z0-9!#$%&'*+/=?^_`{|}~]"; // RFC 5322 unquoted characters in local-part
    $alpha = "a-z\x80-\xFF"; // superset of IDN

    $result = (bool)preg_match(
        "(^
            (\"([ !#-[\\]-~]*|\\\\[ -~])+\"|$atom+(\\.$atom+)*)  # quoted or unquoted
            @
            ([0-9$alpha]([-0-9$alpha]{0,61}[0-9$alpha])?\\.)+    # domain - RFC 1034
            [$alpha]([-0-9$alpha]{0,17}[$alpha])?                # top domain
            \\z)ix",
        $value
    );

    if (false === $result) {
        throw new InvalidArgumentException(sprintf('EMAIL is not valid! "%s" given.', $value));
    }
}

/**
 * @param string $url
 *
 * @throws InvalidArgumentException
 */
function assertUrl(string $url): void
{
    if (false === filter_var($url, FILTER_VALIDATE_URL)) {
        throw new InvalidArgumentException('URL is Invalid.');
    }
}
