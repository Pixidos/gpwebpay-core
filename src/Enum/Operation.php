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
 * @method static Operation CREATE_ORDER()
 * @method static Operation CARD_VERIFICATION()
 * @method static Operation FINALIZE_ORDER()
 */
final class Operation extends Enum
{
    use AutoInstances;

    public const CREATE_ORDER = 'CREATE_ORDER';
    public const CARD_VERIFICATION = 'CARD_VERIFICATION';
    public const FINALIZE_ORDER = 'FINALIZE_ORDER';
}
