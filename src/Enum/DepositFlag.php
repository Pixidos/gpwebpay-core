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

namespace Pixidos\GPWebPay\Enum;

use Grifart\Enum\AutoInstances;
use Grifart\Enum\Enum;

/**
 * @method static DepositFlag YES()
 * @method static DepositFlag NO()
 */
final class DepositFlag extends Enum
{
    use AutoInstances;

    public const YES = 1;
    public const NO = 0;
}
