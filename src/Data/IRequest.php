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

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Param\IParam;

interface IRequest
{
    /**
     * Return all parameters
     * @return array
     */
    public function getParams(): array;

    /**
     * @param IParam $param
     */
    public function setParam(IParam $param): void;

    /**
     * Return only parameters what are included in digest
     * @return array
     */
    public function getDigestParams(): array;

    /**
     * @param bool $asPost
     *
     * @return string
     */
    public function getRequestUrl(bool $asPost = false): string;

    /**
     * Sorting Param by documentation
     */
    public function sortParams(): void;
}
