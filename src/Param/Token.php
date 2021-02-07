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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

use function Pixidos\GPWebPay\assertMaxLenght;

class Token implements IParam
{

    /**
     * @var string
     */
    private $token;

    /**
     * Token constructor.
     *
     * @param string $token max 64 chars
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $token)
    {
        assertMaxLenght($token, 64, $this->getParamName());
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::TOKEN;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->token;
    }
}
