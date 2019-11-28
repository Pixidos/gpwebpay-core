<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Enum;

use MyCLabs\Enum\Enum;

/**
 * Class DepositFlag
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 *
 * @method static DepositFlag YES()
 * @method static DepositFlag NO()
 */
final class DepositFlag extends Enum
{
    public const YES = 1;
    public const NO = 0;
}
