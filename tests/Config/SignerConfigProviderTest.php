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

namespace Pixidos\GPWebPay\Tests\Config;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Config\SignerConfig;
use Pixidos\GPWebPay\Config\SignerConfigProvider;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;

class SignerConfigProviderTest extends TestCase
{

    public function testAddConfig(): void
    {
        $provider = new SignerConfigProvider();
        $config = new SignerConfig(
            __DIR__ . '/../_certs/test.pem',
            '1234567',
            __DIR__ . '/../_certs/test-pub.pem'
        );
        $provider->addConfig(
            $config,
            'czk'
        );

        self::assertSame($config, $provider->getConfig('czk'));
    }


    public function testNotExistingConfigThrowException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Config for gateway "czk" does not exist. You are probably forgot added.');

        $provider = new SignerConfigProvider();
        $provider->getConfig('czk');
    }
}
