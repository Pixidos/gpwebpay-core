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

namespace Pixidos\GPWebPay\Config\Factory;

use Pixidos\GPWebPay\Config\Config;

interface ConfigFactoryInterface
{

    /**
     * @param mixed[]  $params
     * @param string $defaultGateway
     * @return Config
     */
    public function create(array $params, string $defaultGateway = 'default'): Config;
}
