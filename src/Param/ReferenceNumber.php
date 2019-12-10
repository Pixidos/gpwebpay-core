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

/**
 * Class ReferenceNumber
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class ReferenceNumber implements IParam
{

    private const PATTERN = '#(^[\dA-Za-z \#\$\*\+,\-.\/:;=\^_@]{1,20}$)#';

    /**
     * @var string
     */
    private $value;

    /**
     * ReferenceNumber constructor.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function getParamName(): string
    {
        return Param::REFERENCENUMBER;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Interní ID u obchodníka Podporované ASCII znaky: x20(space), x23(#), x24($), x2A-x3B(*+,-./0-9:;), x3D(=), x40-x5A(@A-Z), x5E(^), x5F(_), x61-x7A(a-z)
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate(string $value): void
    {
        if ((bool)preg_match(self::PATTERN, $value) === false) {
            throw new InvalidArgumentException(
                sprintf('%s has invalid lenght(max 20) or containt invalid char. Alowed chars are(0-9A-Za-z(space)#*+,-./:;=@^_).', $this->getParamName())
            );
        }
    }

}
