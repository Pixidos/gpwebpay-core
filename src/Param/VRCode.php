<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertMaxLenght;

/**
 * Class Token
 * @package Pixidos\GPWebPay\Param
 * @author Ondra Votava <ondra@votava.it>
 */
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
