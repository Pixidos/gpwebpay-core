<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Data;

/**
 * This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */
final class ResponseError
{

    private const CODES = [
        'cz' => [
            28 => [
                0 => 'Zamítnuto v 3D',
                3000 => 'Neověřeno v 3D. Vydavatel karty není zapojen do 3D nebo karta nebyla aktivována',
                3002 => 'Neověřeno v 3D. Vydavatel karty nebo karta není zapojena do 3D',
                3004 => 'Neověřeno v 3D. Vydavatel karty není zapojen do 3D nebo karta nebyla aktivována',
                3005 => 'Zamítnuto v 3D. Technický problém při ověření držitele karty',
                3006 => 'Zamítnuto v 3D. Technický problém při ověření držitele karty',
                3007 => 'Zamítnuto v 3D. Technický problém v systému zůčtující banky. Kontaktujte obchodníka',
                3008 => 'Zamítnuto v 3D. Použit nepodoporavný karetní produkt',
            ],
            30 => [
                0 => 'Zamitnuto v autorizacnim centru',
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
                0 => 'Declined in 3D',
                3000 => 'Not Authenticated in 3D. Cardholder not authenticated in 3D.',
                3002 => 'Not Authenticated in 3D. Issuer or Cardholder not participating in 3D.',
                3004 => 'Not Authenticated in 3D. Issuer not participating or Cardholder not enrolled.',
                3005 => 'Declined in 3D. Technical problem during Cardholder authentication.',
                3006 => 'Declined in 3D. Technical problem during Cardholder authentication.',
                3007 => 'Declined in 3D. Acquirer technical problem. Contact the merchant.',
                3008 => 'Declined in 3D. Unsupported card product.',
            ],
            30 => [
                0 => 'Declined in AC',
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
     * @var int
     */
    private $srcode;
    /**
     * @var int
     */
    private $prcode;

    /**
     * ResponseError constructor.
     * @param int $prcode
     * @param int $srcode
     */
    public function __construct(int $prcode, int $srcode)
    {
        $this->prcode = $prcode;
        $this->srcode = $srcode;
    }

    /**
     * @param string $lang
     *
     * @return string
     */
    public function getMessage(string $lang): string
    {
        $default = $lang === 'cz' ? 'Technický problém v systému, kontaktujete obchodníka'
            : 'Technical problem in system, contact the merchant.';

        if (isset(self::CODES[$lang][$this->prcode][$this->srcode])) {
            return self::CODES[$lang][$this->prcode][$this->srcode];
        }

        return $default;
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
}
