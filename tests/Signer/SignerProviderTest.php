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

namespace Pixidos\GPWebPay\Tests\Signer;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;
use Pixidos\GPWebPay\Tests\TestHelpers;

class SignerProviderTest extends TestCase
{

    public function testSuccessCreateSigner(): void
    {
        $provider = new SignerProvider(new SignerFactory(), TestHelpers::createConfig()->getSignerConfigProvider());

        $signerCzk = $provider->get(TestHelpers::CZK);
        $signerEur = $provider->get(TestHelpers::EUR);

        self::assertNotEquals($signerCzk, $signerEur);
    }

    public function testSignerWillCreateOnlyOneTime(): void
    {
        $provider = new SignerProvider(new SignerFactory(), TestHelpers::createConfig()->getSignerConfigProvider());

        $signer1 = $provider->get(TestHelpers::CZK);
        $signer2 = $provider->get(TestHelpers::CZK);

        self::assertSame($signer1, $signer2);
    }
}
