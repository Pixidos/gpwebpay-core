<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Exceptions;

use Throwable;

/**
 * Class GPWebPayResultException
 * @package Pixidos\GPWebPay\Exceptions
 * @author Ondra Votava <ondra.votava@pixidos.com>
 */
class GPWebPayResultException extends GPWebPayException
{

    private $codes = [
        'cz' => [
            28 => [
                'message' => 'Zamítnuto v 3D',
                3000 => 'Neověřeno v 3D. Vydavatel karty není zapojen do 3D nebo karta nebyla aktivována',
                3002 => 'Neověřeno v 3D. Vydavatel karty nebo karta není zapojena do 3D',
                3004 => 'Neověřeno v 3D. Vydavatel karty není zapojen do 3D nebo karta nebyla aktivována',
                3005 => 'Zamítnuto v 3D. Technický problém při ověření držitele karty',
                3006 => 'Zamítnuto v 3D. Technický problém při ověření držitele karty',
                3007 => 'Zamítnuto v 3D. Technický problém v systému zůčtující banky. Kontaktujte obchodníka',
                3008 => 'Zamítnuto v 3D. Použit nepodoporavný katetní produkt',
            ],
            30 => [
                'message' => 'Zamitnuto v autorizacnim centru',
                1001 => 'Zamitnuto v autorizacnim centru, katra blokována',
                1002 => 'Zamitnuto v autorizacnim centru, autorizace zamítnuta',
                1003 => 'Zamitnuto v autorizacnim centru, problém karty',
                1004 => 'Zamitnuto v autorizacnim centru, technický problém',
                1005 => 'Zamitnuto v autorizacnim centru, Problém ctu',
            ],
            1000 => 'Technický problém',
        ],
        'en' => [
            28 => [
                'message' => 'Declined in 3D',
                3000 => 'Not Authenticated in 3D. Cardholder not authenticated in 3D.',
                3002 => 'Not Authenticated in 3D. Issuer or Cardholder not participating in 3D.',
                3004 => 'Not Authenticated in 3D. Issuer not participating or Cardholder not enrolled.',
                3005 => 'Declined in 3D. Technical problem during Cardholder authentication.',
                3006 => 'Declined in 3D. Technical problem during Cardholder authentication.',
                3007 => 'Declined in 3D. Acquirer technical problem. Contact the merchant.',
                3008 => 'Declined in 3D. Unsupported card product.',
            ],
            30 => [
                'message' => 'Declined in AC',
                1001 => 'Declined in AC, Card blocked',
                1002 => 'Declined in AC, Declined',
                1003 => 'Declined in AC, Card problem',
                1004 => 'Declined in AC, Technical problem in authorization process',
                1005 => 'Declined in AC, Account problem',
            ],
            1000 => 'Technical problem',

        ],
    ];

    /**
     * @var int $prcode
     */
    private $prcode;
    /**
     * @var int $srcode
     */
    private $srcode;
    /**
     * @var string|null $resulttext
     */
    private $resulttext;

    /**
     * GPWebPayResultException constructor.
     *
     * @param string         $message
     * @param int            $prcode
     * @param int            $srcode
     * @param string|null    $resulttext
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $prcode, int $srcode, ?string $resulttext = null, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->prcode = $prcode;
        $this->srcode = $srcode;
        $this->resulttext = $resulttext;
    }

    /**
     * @return int
     */
    public function getPrcode(): int
    {
        return $this->prcode;
    }

    /**
     * @return int
     */
    public function getSrcode(): int
    {
        return $this->srcode;
    }

    /**
     * @return null|string
     */
    public function getResultText(): ?string
    {
        return $this->resulttext;
    }

    /**
     * @param string $lang
     *
     * @return string
     */
    public function translate(string $lang): string
    {
        switch ($lang) {
            case 'cz':
                if ($this->prcode === 28 || $this->prcode === 30 || $this->prcode === 1000) {
                    return $this->codes['cz'][$this->prcode][$this->srcode];
                }

                return 'Technický problém v systému, kontaktujete obchodníka';

                break;
            case 'en':
                if ($this->prcode === 28 || $this->prcode === 30 || $this->prcode === 1000) {
                    return $this->codes['en'][$this->prcode][$this->srcode];
                }

                return 'Technical problem in system, contact the merchant.';
                break;
            default:
                return 'unsupported language';
        }

    }
}
