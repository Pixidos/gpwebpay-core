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
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertLenght;

class Lang implements IParam
{
    /**
     * @var string
     */
    private $value;

    /**
     * Lang constructor.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        assertLenght($value, 2, $this->getParamName());
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::LANG;
    }
}
