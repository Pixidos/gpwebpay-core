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

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;

class DisablePayMethod implements IParam
{
    /**
     * @var PayMethodEnum
     */
    private $value;

    /**
     * DisablePayMethod constructor.
     *
     * @param PayMethodEnum $method
     */
    public function __construct(PayMethodEnum $method)
    {
        $this->value = $method;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->value;
    }


    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::DISABLEPAYMETHOD;
    }

    /**
     * @return PayMethodEnum
     */
    public function getValue(): PayMethodEnum
    {
        return $this->value;
    }

}
