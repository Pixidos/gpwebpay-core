<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Tests\Signer;

use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Exceptions\SignerException;
use Pixidos\GPWebPay\Signer\Signer;

class SignerTest extends TestCase
{

    /**
     * @throws SignerException
     */
    public function testCreateSign(): void
    {
        $signer = new Signer(__DIR__ . '/../_certs/test.pem', '1234567', __DIR__ . '/../_certs/test-pub.pem');
        $hash = $signer->sign(
            [
                'MERCHANTNUMBER' => '123456789',
                'DEPOSITFLAG' => '1',
                'OPERATION' => 'CREATE_ORDER',
                'AMOUNT' => '100000',
                'ORDERNUMBER' => '123456',
                'CURRENCY' => '203',
                'MD' => 'czk',
                'URL' => 'http://test.com',
            ]
        );

        self::assertSame(
            'kMl9tg/up2z9CJu+Ebgm7mg3XSGOAvY2ZkrtgqOtzSprh1L22bvshGRlfDT9134Z2Hj1PWNitDOvgoAFnxyax8oIyx6eB4hMnNkB6xyr3X5XQXqsCsVRGYHYUOLvNuAag1kaNcVx+'
            . 'juqijxd0huvk60PMn5JjQijNl4ij36YwoqyN4UdP16LjIqYRIngaeHsTTR1XgIVmJIcuIfETV1QsiQCOYPw0s/ZTeri1DzpQq1Es5cERSupFBVp5Y8tJUna0Yx/oLh2SBhsw6BPixm6jhLAj'
            . 'qvQn+gmMv4AKDfTYdSPDqg1A+T3XFK/+vE+zOGW0/DHKr2ZqNYUQyD1adi3QA==',
            $hash
        );
    }

    /**
     * @throws SignerException
     */
    public function testSuccessVerify(): void
    {
        $signer = new Signer(__DIR__ . '/../_certs/test.pem', '1234567', __DIR__ . '/../_certs/test-pub.pem');
        $params = [
            'MERCHANTNUMBER' => '123456789',
            'DEPOSITFLAG' => '1',
            'OPERATION' => 'CREATE_ORDER',
            'AMOUNT' => '100000',
            'ORDERNUMBER' => '123456',
            'CURRENCY' => '203',
            'MD' => 'czk',
            'URL' => 'http://test.com',
        ];
        $hash = $signer->sign($params);

        $result = $signer->verify($params, $hash);

        self::assertTrue($result);
    }

    /**
     * @throws SignerException
     */
    public function testFailedVerify(): void
    {

        $signer = new Signer(__DIR__ . '/../_certs/test.pem', '1234567', __DIR__ . '/../_certs/test-pub.pem');
        $params = [
            'MERCHANTNUMBER' => '123456789',
            'DEPOSITFLAG' => '1',
            'OPERATION' => 'CREATE_ORDER',
            'AMOUNT' => '100000',
            'ORDERNUMBER' => '123456',
            'CURRENCY' => '203',
            'MD' => 'czk',
            'URL' => 'http://test.com',
        ];

        $result = $signer->verify($params, 'badhash');

        self::assertFalse($result);

    }
}
