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
use function Pixidos\GPWebPay\assertMaxLenght;

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
