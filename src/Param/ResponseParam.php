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

class ResponseParam implements IParam
{

    /**
     * @var string
     */
    private $value;
    /**
     * @var string
     */
    private $name;

    /**
     * Email constructor.
     *
     * @param string $value
     * @param string $name
     */
    public function __construct(string $value, string $name)
    {
        $value = trim($value);
        $this->value = $value;
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getParamName(): string
    {
        return $this->name;
    }
}
