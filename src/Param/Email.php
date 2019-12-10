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
use function Pixidos\GPWebPay\assertIsEmail;
use function Pixidos\GPWebPay\assertMaxLenght;

/**
 * Class Email
 * @package Pixidos\GPWebPay\Data
 * @author Ondra Votava <ondra@votava.it>
 */
class Email implements IParam
{

    /**
     * @var string
     */
    private $value;

    /**
     * Email constructor.
     *
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $value)
    {
        $value = trim($value);
        $this->validate($value);
        $this->value = $value;
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
        return Param::EMAIL;
    }

    /**
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    protected function validate(string $value): void
    {
        assertIsEmail($value);
        assertMaxLenght($value, 255, 'EMAIL');
    }

}
