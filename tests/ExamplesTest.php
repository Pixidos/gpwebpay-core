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

namespace Pixidos\GPWebPay\Tests;

use DOMDocument;
use DOMElement;
use PHPUnit\Framework\TestCase;
use Pixidos\GPWebPay\Signer\SignerFactory;
use Pixidos\GPWebPay\Signer\SignerProvider;

/**
 * Runs the published examples (also included in the documentation) with the test keys from phpunit.xml.
 *
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ExamplesTest extends TestCase
{
    private const EXAMPLES = __DIR__ . '/../example/';

    public function testCreateRequestRendersSignedPostForm(): void
    {
        $form = $this->createRequestForm();

        self::assertSame('post', $form['method']);
        self::assertSame('https://test.3dsecure.gpwebpay.com/pgw/order.do', $form['action']);
        self::assertSame(
            [
                'MERCHANTNUMBER', 'OPERATION', 'ORDERNUMBER', 'AMOUNT', 'CURRENCY', 'DEPOSITFLAG', 'MERORDERNUM', 'URL',
                'PAYMETHODS', 'ADDINFO', 'DIGEST',
            ],
            array_keys($form['fields'])
        );
        self::assertSame('7001', $form['fields']['ORDERNUMBER']);
        self::assertSame('129900', $form['fields']['AMOUNT']);
        self::assertSame('CRD,GPAY,APAY', $form['fields']['PAYMETHODS']);
        self::assertStringContainsString(
            '<cardholderDetails><name>Jan Novak</name><email>jan@example.com</email></cardholderDetails>',
            $form['fields']['ADDINFO']
        );

        $signed = $form['fields'];
        unset($signed['DIGEST']);
        self::assertTrue($this->signers()->get('czk')->verify($signed, $form['fields']['DIGEST']));
    }

    public function testHandleResponseAcceptsPostResponse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $this->signedResponse('0', '0');
        $_GET = ['OPERATION' => 'CREATE_ORDER', 'ORDERNUMBER' => '1'];

        self::assertSame('Payment accepted.', $this->runExample('handle_response.php'));
    }

    public function testHandleResponseAcceptsGetResponse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = $this->signedResponse('0', '0');
        $_POST = [];

        self::assertSame('Payment accepted.', $this->runExample('handle_response.php'));
    }

    public function testHandleResponseReportsDeclinedPayment(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = $this->signedResponse('30', '1002', 'Declined in AC, Declined');

        self::assertSame('Payment failed: Declined in AC, Declined', $this->runExample('handle_response.php'));
    }

    public function testHandleResponseRejectsForgedResponse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $this->signedResponse('30', '1002', 'Declined in AC, Declined');
        $_POST['PRCODE'] = '0';
        $_POST['SRCODE'] = '0';

        self::assertSame('Invalid payment response.', $this->runExample('handle_response.php'));
    }

    public function testHandleResponseRejectsMalformedResponse(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = ['ORDERNUMBER' => ['7001']];

        self::assertSame('Invalid payment response.', $this->runExample('handle_response.php'));
    }

    /**
     * @return array<string, string>
     */
    private function signedResponse(string $prcode, string $srcode, ?string $resultText = null): array
    {
        $fields = [
            'OPERATION' => 'CREATE_ORDER',
            'ORDERNUMBER' => '7001',
            'MERORDERNUM' => '2026000123',
            'PRCODE' => $prcode,
            'SRCODE' => $srcode,
        ];
        if (null !== $resultText) {
            $fields['RESULTTEXT'] = $resultText;
        }

        return TestHelpers::signAsGateway($this->signers()->get('czk'), $fields, TestHelpers::CZK_MERCHANT_NUMBER);
    }

    private function signers(): SignerProvider
    {
        return new SignerProvider(
            new SignerFactory(),
            TestHelpers::createMultiGatewayConfig()->getSignerConfigProvider()
        );
    }

    /**
     * @return array{method: string, action: string, fields: array<string, string>}
     */
    private function createRequestForm(): array
    {
        $document = new DOMDocument();
        $document->loadHTML($this->runExample('create_request.php'));
        $form = $document->getElementsByTagName('form')->item(0);
        self::assertInstanceOf(DOMElement::class, $form);

        $fields = [];
        foreach ($form->getElementsByTagName('input') as $input) {
            $fields[$input->getAttribute('name')] = $input->getAttribute('value');
        }

        return [
            'method' => $form->getAttribute('method'),
            'action' => $form->getAttribute('action'),
            'fields' => $fields,
        ];
    }

    private function runExample(string $file): string
    {
        ob_start();
        try {
            (static function (string $path): void {
                require $path;
            })(self::EXAMPLES . $file);
        } finally {
            $output = (string)ob_get_clean();
        }

        return $output;
    }
}
