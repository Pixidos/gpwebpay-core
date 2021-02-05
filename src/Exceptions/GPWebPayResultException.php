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

namespace Pixidos\GPWebPay\Exceptions;

use Pixidos\GPWebPay\Data\ResponseError;
use Throwable;

class GPWebPayResultException extends GPWebPayException
{
    /**
     * @var string|null $resultText
     */
    private $resultText;
    /**
     * @var ResponseError
     */
    private $error;

    /**
     * GPWebPayResultException constructor.
     *
     * @param string         $message
     * @param int            $prCode
     * @param int            $srCode
     * @param string|null    $resultText
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message,
        int $prCode,
        int $srCode,
        ?string $resultText = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->resultText = $resultText;
        $this->error = new ResponseError($prCode, $srCode);
    }

    /**
     * @return int
     */
    public function getPrcode(): int
    {
        return $this->error->getPrcode();
    }

    /**
     * @return int
     */
    public function getSrcode(): int
    {
        return $this->error->getSrcode();
    }

    /**
     * @return null|string
     */
    public function getResultText(): ?string
    {
        return $this->resultText;
    }

    /**
     * @param string $lang
     *
     * @return string
     */
    public function translate(string $lang): string
    {
        return $this->error->getMessage($lang);
    }
}
