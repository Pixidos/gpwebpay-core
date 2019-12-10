<?php declare(strict_types=1);

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.it>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

namespace Pixidos\GPWebPay\Enum;

use Grifart\Enum\AutoInstances;
use Grifart\Enum\Enum;

/**
 * Class Operation
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 *
 * @method static Operation CREATE_ORDER()
 */
final class Operation extends Enum
{
    use AutoInstances;

    public const CREATE_ORDER = 'CREATE_ORDER';
}
