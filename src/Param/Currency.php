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

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Currency as CurrencyEnum;
use Pixidos\GPWebPay\Enum\Param;

class Currency implements IParam
{
    /**
     * @var CurrencyEnum
     */
    private $value;

    public function __construct(CurrencyEnum $currency)
    {
        $this->value = $currency;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }


    public function getParamName(): string
    {
        return Param::CURRENCY;
    }

    /**
     * @return CurrencyEnum
     */
    public function getValue(): CurrencyEnum
    {
        return $this->value;
    }

}
