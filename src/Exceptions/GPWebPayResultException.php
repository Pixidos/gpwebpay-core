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
    private string|null $resultText;
    private ResponseError $error;

    public function __construct(
        string $message,
        int $prCode,
        int $srCode,
        ?string $resultText = null,
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->resultText = $resultText;
        $this->error = new ResponseError($prCode, $srCode);
    }

    public function getPrcode(): int
    {
        return $this->error->getPrcode();
    }

    public function getSrcode(): int
    {
        return $this->error->getSrcode();
    }


    public function getResultText(): ?string
    {
        return $this->resultText;
    }

    public function translate(string $lang): string
    {
        return $this->error->getMessage($lang);
    }
}
