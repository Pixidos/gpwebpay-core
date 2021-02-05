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

namespace Pixidos\GPWebPay\Enum;

use Grifart\Enum\AutoInstances;
use Grifart\Enum\Enum;
use Stringable;

/**
 * @method static PayMethod CARD()
 * @method static PayMethod MASTERCARD_MOBILE()
 * @method static PayMethod MASTERPASS()
 * @method static PayMethod GOOGLE_PAY()
 * @method static PayMethod PLATBA24()
 * @method static PayMethod BANK_ACCOUNT()
 * @method static PayMethod BANK_KB()
 * @method static PayMethod BANK_CSOB()
 * @method static PayMethod BANK_MONETA()
 * @method static PayMethod BANK_CNB()
 * @method static PayMethod BANK_CS()
 * @method static PayMethod BANK_FIO()
 * @method static PayMethod BANK_UCB()
 * @method static PayMethod BANK_AIRB()
 * @method static PayMethod BANK_RB()
 * @method static PayMethod BANK_MBANK()
 * @method static PayMethod BANK_SBERBANK()
 */
final class PayMethod extends Enum implements Stringable
{
    use AutoInstances;

    public const CARD = 'CRD';
    public const MASTERCARD_MOBILE = 'MCM';
    public const MASTERPASS = 'MPS';
    public const GOOGLE_PAY = 'GPAY';
    public const PLATBA24 = 'BTNCS';
    public const BANK_ACCOUNT = 'BTN360CS'; // Platba z účtu
    public const BANK_KB = 'BTN360CS-0100'; // Platba z účtu – Komerční banka, a.s.
    public const BANK_CSOB = 'BTN360CS-0300'; // Platba z účtu – Československá obchodní banka, a. s.
    public const BANK_MONETA = 'BTN360CS-0600'; // Platba z účtu – MONETA Money Bank, a. s.
    public const BANK_CNB = 'BTN360CS-0710'; // Platba z účtu – Česká národní banka
    public const BANK_CS = 'BTN360CS-0800'; // Platba z účtu – Česká spořitelna, a.s.
    public const BANK_FIO = 'BTN360CS-2010'; // Platba z účtu – Fio banka, a.s.
    public const BANK_UCB = 'BTN360CS-2700'; // Platba z účtu – UniCredit Bank Czech Republic and Slovakia, a.s.
    public const BANK_AIRB = 'BTN360CS-3030'; // Platba z účtu – Air Bank a.s.
    public const BANK_RB = 'BTN360CS-5500'; // Platba z účtu – Raiffeisenbank a.s.
    public const BANK_MBANK = 'BTN360CS-6210'; // Platba z účtu – mBank S.A.
    public const BANK_SBERBANK = 'BTN360CS-6800'; // Platba z účtu – Sberbank CZ, a.s.
}
