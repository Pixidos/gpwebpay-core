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
 * Class Param
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 *
 * @method static Param MERCHANTNUMBER()
 * @method static Param OPERATION()
 * @method static Param ORDERNUMBER()
 * @method static Param AMOUNT()
 * @method static Param CURRENCY()
 * @method static Param DEPOSITFLAG()
 * @method static Param MERORDERNUM()
 * @method static Param RESPONSE_URL()
 * @method static Param DESCRIPTION()
 * @method static Param MD()
 * @method static Param USERPARAM()
 * @method static Param FASTPAYID()
 * @method static Param PAYMETHOD()
 * @method static Param DISABLEPAYMETHOD()
 * @method static Param PAYMETHODS()
 * @method static Param EMAIL()
 * @method static Param REFERENCENUMBER()
 * @method static Param ADDINFO()
 * @method static Param LANG()
 * @method static Param DIGEST()
 * @method static Param TOKEN()
 * @method static Param FAST_TOKEN()
 * @method static Param VRCODE()
 * @method static Param PANPATTERN()
 *
 */
final class Param extends Enum
{

    use AutoInstances;

    public const MERCHANTNUMBER = 'MERCHANTNUMBER';
    public const OPERATION = 'OPERATION';
    public const ORDERNUMBER = 'ORDERNUMBER';
    public const AMOUNT = 'AMOUNT';
    public const CURRENCY = 'CURRENCY';
    public const DEPOSITFLAG = 'DEPOSITFLAG';
    public const MERORDERNUM = 'MERORDERNUM';
    public const RESPONSE_URL = 'URL';
    public const DESCRIPTION = 'DESCRIPTION';
    public const MD = 'MD';
    public const USERPARAM = 'USERPARAM1';
    public const FASTPAYID = 'FASTPAYID';
    public const PAYMETHOD = 'PAYMETHOD';
    public const DISABLEPAYMETHOD = 'DISABLEPAYMETHOD';
    public const PAYMETHODS = 'PAYMETHODS';
    public const EMAIL = 'EMAIL';
    public const REFERENCENUMBER = 'REFERENCENUMBER';
    public const ADDINFO = 'ADDINFO';
    public const LANG = 'LANG';
    public const DIGEST = 'DIGEST';
    public const TOKEN = 'TOKEN';
    public const FAST_TOKEN = 'FASTTOKEN';
    public const VRCODE = 'VRCODE';
    public const PANPATTERN = 'PANPATTERN';
}
