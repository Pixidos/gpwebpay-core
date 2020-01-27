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

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertMaxLenght;

class VRCode implements IParam
{

    /**
     * @var string
     */
    private $code;

    /**
     * Token constructor.
     *
     * @param string $code max 48 chars
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $code)
    {
        assertMaxLenght($code, 48, $this->getParamName());
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::VRCODE;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->code;
    }

}
