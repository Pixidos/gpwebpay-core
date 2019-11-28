<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Param;

use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use function Pixidos\GPWebPay\assertMaxLenght;

/**
 * Class AddInfo
 * @package Pixidos\GPWebPay\Param
 * @author Ondra Votava <ondra@votava.it>
 */
class AddInfo implements IParam
{
    /**
     * @var string
     */
    private $xml;

    /**
     * AddInfo constructor.
     *
     * @param string $xml
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $xml)
    {
        assertMaxLenght($xml, 24000, $this->getParamName());
        $this->xml = $xml;
    }

    /**
     * @return string
     */
    public function getParamName(): string
    {
        return Param::ADDINFO;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->xml;
    }


    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->xml;
    }

}
