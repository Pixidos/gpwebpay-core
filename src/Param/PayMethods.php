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

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Enum\PayMethod as PayMethodEnum;

class PayMethods implements IParam
{
    /**
     * @var array<PayMethodEnum>
     */
    private array $methods;
    /**
     * @var string
     */
    private string $string;


    /**
     * PayMethods constructor.
     *
     * @param PayMethodEnum ...$methods
     */
    public function __construct(PayMethodEnum ...$methods)
    {
        $this->methods = array_unique($methods);
        $this->createString();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->string;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::PAYMETHODS;
    }

    /**
     * @return array<PayMethodEnum>
     */
    public function getValue(): array
    {
        return $this->methods;
    }

    /**
     * @param PayMethodEnum $method
     */
    public function addMethod(PayMethodEnum $method): void
    {
        $this->methods[] = $method;
        $this->methods = array_unique($this->methods);
        $this->createString();
    }

    private function createString(): void
    {
        $string = implode(',', $this->methods);
        $this->string = $string;
    }
}
