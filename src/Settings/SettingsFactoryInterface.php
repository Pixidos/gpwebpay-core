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

namespace Pixidos\GPWebPay\Settings;

interface SettingsFactoryInterface
{
    /**
     * @param array<string>|string $privateKey
     * @param array<string>|string $privateKeyPassword
     * @param array<string>|string $publicKey
     * @param array<string>|string $url
     * @param array<string>|string $merchantNumber
     * @param array<int>|int $depositFlag
     * @param string $defaultGatewayKey
     *
     * @return Settings
     */
    public static function create(
        $privateKey,
        $privateKeyPassword,
        $publicKey,
        $url,
        $merchantNumber,
        $depositFlag = 1,
        string $defaultGatewayKey = 'czk'
    ): Settings;
}
