<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class Operation
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 *
 * @method static Operation CREATE_ORDER()
 */
final class Operation extends Enum
{
    public const CREATE_ORDER = 'CREATE_ORDER';
}
