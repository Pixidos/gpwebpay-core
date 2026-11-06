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

namespace Pixidos\GPWebPay\Exceptions;

/**
 * The received response data cannot be a GP webpay response for this configuration:
 * a mandatory field is missing, a value has an invalid type or format,
 * or MD routes the response to a gateway that is not configured.
 *
 * Response data come from the customer's browser, so treat this as untrusted input
 * and reject the request. It never means that the payment succeeded.
 */
class InvalidResponseException extends GPWebPayException
{
}
