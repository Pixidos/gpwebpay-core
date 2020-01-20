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

namespace Pixidos\GPWebPay\Tests\Signer;

use Pixidos\GPWebPay\Signer\SignerFactory;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Tests\TestHelpers;

class SignerFactoryTest extends TestCase
{

    public function testSuccessCreateSigner(): void
    {
        $factory = new SignerFactory(TestHelpers::createSettings());

        $signerCzk = $factory->create(TestHelpers::CZK);
        $signerEur = $factory->create(TestHelpers::EUR);

        self::assertNotEquals($signerCzk, $signerEur);
    }

    public function testSignerWillCreateOnlyOneTime(): void
    {
        $factory = new SignerFactory(TestHelpers::createSettings());

        $signer1 = $factory->create(TestHelpers::CZK);
        $signer2 = $factory->create(TestHelpers::CZK);

        self::assertSame($signer1, $signer2);
    }
}
