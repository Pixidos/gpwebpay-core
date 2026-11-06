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

namespace Pixidos\GPWebPay\Enum;

use Grifart\Enum\AutoInstances;
use Grifart\Enum\Enum;
use Stringable;

/**
 * Values of the PAYMETHOD and PAYMETHODS request fields (GP webpay HTTP API 1.19, appendix 5).
 * Which methods a merchant may use depends on the provider (acquirer) and the contract.
 *
 * @method static PayMethod CARD()
 * @method static PayMethod GOOGLE_PAY()
 * @method static PayMethod APPLE_PAY()
 * @method static PayMethod PAYPAL()
 * @method static PayMethod CLICK_TO_PAY()
 * @method static PayMethod APM_ALL()
 * @method static PayMethod APM_CZ_CESKA_SPORITELNA()
 * @method static PayMethod APM_CZ_KOMERCNI_BANKA()
 * @method static PayMethod APM_CZ_CSOB()
 * @method static PayMethod APM_CZ_RAIFFEISENBANK()
 * @method static PayMethod APM_CZ_MBANK()
 * @method static PayMethod APM_CZ_FIO_BANKA()
 * @method static PayMethod APM_CZ_MONETA()
 * @method static PayMethod APM_CZ_AIR_BANK()
 * @method static PayMethod APM_CZ_QR()
 * @method static PayMethod TWISTO_PAY_IN_30_DAYS()
 * @method static PayMethod TWISTO_PAY_IN_THIRDS()
 * @method static PayMethod APM_AT_EPS()
 * @method static PayMethod APM_SK_SLOVENSKA_SPORITELNA()
 * @method static PayMethod APM_SK_TATRA_BANKA()
 * @method static PayMethod APM_SK_VUB()
 * @method static PayMethod APM_SK_CSOB()
 * @method static PayMethod APM_SK_PRIMA_BANKA()
 * @method static PayMethod APM_SK_QR()
 * @method static PayMethod PLATBA24() being phased out by the provider
 * @method static PayMethod SOFORT() being phased out by the provider
 * @method static PayMethod EPS() being phased out by the provider, see APM_AT_EPS()
 * @method static PayMethod PAYSAFECARD() being phased out by the provider
 * @method static PayMethod SEPA_DIRECT_DEBIT() being phased out by the provider
 * @method static PayMethod KLARNA() being phased out by the provider
 * @method static PayMethod MASTERCARD_MOBILE() deprecated, not in HTTP API 1.19
 * @method static PayMethod MASTERPASS() deprecated, removed in HTTP API 1.14
 * @method static PayMethod BANK_ACCOUNT() deprecated, removed in HTTP API 1.14, use the APM_* methods
 * @method static PayMethod BANK_KB() deprecated, removed in HTTP API 1.14, use APM_CZ_KOMERCNI_BANKA()
 * @method static PayMethod BANK_CSOB() deprecated, removed in HTTP API 1.14, use APM_CZ_CSOB()
 * @method static PayMethod BANK_MONETA() deprecated, removed in HTTP API 1.14, use APM_CZ_MONETA()
 * @method static PayMethod BANK_CNB() deprecated, removed in HTTP API 1.14
 * @method static PayMethod BANK_CS() deprecated, removed in HTTP API 1.14, use APM_CZ_CESKA_SPORITELNA()
 * @method static PayMethod BANK_FIO() deprecated, removed in HTTP API 1.14, use APM_CZ_FIO_BANKA()
 * @method static PayMethod BANK_UCB() deprecated, removed in HTTP API 1.14
 * @method static PayMethod BANK_AIRB() deprecated, removed in HTTP API 1.14, use APM_CZ_AIR_BANK()
 * @method static PayMethod BANK_RB() deprecated, removed in HTTP API 1.14, use APM_CZ_RAIFFEISENBANK()
 * @method static PayMethod BANK_MBANK() deprecated, removed in HTTP API 1.14, use APM_CZ_MBANK()
 * @method static PayMethod BANK_SBERBANK() deprecated, removed in HTTP API 1.14
 */
final class PayMethod extends Enum implements Stringable
{
    use AutoInstances;

    public const CARD = 'CRD';
    public const GOOGLE_PAY = 'GPAY';
    public const APPLE_PAY = 'APAY';
    public const PAYPAL = 'PAYPAL';
    public const CLICK_TO_PAY = 'CTP';

    // Alternative payment methods (APM) provided by GPE
    public const APM_ALL = 'APM-BTR'; // all APM methods available to the merchant
    public const APM_CZ_CESKA_SPORITELNA = 'APM-BCCS';
    public const APM_CZ_KOMERCNI_BANKA = 'APM-BCKB';
    public const APM_CZ_CSOB = 'APM-BCOB';
    public const APM_CZ_RAIFFEISENBANK = 'APM-BCRB';
    public const APM_CZ_MBANK = 'APM-BCMB';
    public const APM_CZ_FIO_BANKA = 'APM-BCFI';
    public const APM_CZ_MONETA = 'APM-BCMO';
    public const APM_CZ_AIR_BANK = 'APM-BCAI';
    public const APM_CZ_QR = 'APM-BCQR'; // QR payment, availability must be verified with the provider
    public const TWISTO_PAY_IN_30_DAYS = 'LCTD';
    public const TWISTO_PAY_IN_THIRDS = 'LCTT';
    public const APM_AT_EPS = 'APM-BAEB';
    public const APM_SK_SLOVENSKA_SPORITELNA = 'APM-BSSS';
    public const APM_SK_TATRA_BANKA = 'APM-BSTB';
    public const APM_SK_VUB = 'APM-BSVB';
    public const APM_SK_CSOB = 'APM-BSOB';
    public const APM_SK_PRIMA_BANKA = 'APM-BSPR';
    public const APM_SK_QR = 'APM-BSQR'; // Pay By Square, availability must be verified with the provider

    // Methods the provider is phasing out
    public const PLATBA24 = 'BTNCS';
    public const SOFORT = 'SOFORT';
    public const EPS = 'EPS';
    public const PAYSAFECARD = 'PAYSAFECARD';
    public const SEPA_DIRECT_DEBIT = 'SEPADIRECTDEBIT';
    public const KLARNA = 'KLARNA';

    /** @deprecated not listed in GP webpay HTTP API 1.19 */
    public const MASTERCARD_MOBILE = 'MCM';
    /** @deprecated Masterpass was removed in GP webpay HTTP API 1.14 */
    public const MASTERPASS = 'MPS';
    /** @deprecated "Platba z účtu" was removed in GP webpay HTTP API 1.14, use the APM_* methods */
    public const BANK_ACCOUNT = 'BTN360CS'; // Platba z účtu
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_KOMERCNI_BANKA */
    public const BANK_KB = 'BTN360CS-0100'; // Platba z účtu – Komerční banka, a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_CSOB */
    public const BANK_CSOB = 'BTN360CS-0300'; // Platba z účtu – Československá obchodní banka, a. s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_MONETA */
    public const BANK_MONETA = 'BTN360CS-0600'; // Platba z účtu – MONETA Money Bank, a. s.
    /** @deprecated removed in GP webpay HTTP API 1.14 */
    public const BANK_CNB = 'BTN360CS-0710'; // Platba z účtu – Česká národní banka
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_CESKA_SPORITELNA */
    public const BANK_CS = 'BTN360CS-0800'; // Platba z účtu – Česká spořitelna, a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_FIO_BANKA */
    public const BANK_FIO = 'BTN360CS-2010'; // Platba z účtu – Fio banka, a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14 */
    public const BANK_UCB = 'BTN360CS-2700'; // Platba z účtu – UniCredit Bank Czech Republic and Slovakia, a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_AIR_BANK */
    public const BANK_AIRB = 'BTN360CS-3030'; // Platba z účtu – Air Bank a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_RAIFFEISENBANK */
    public const BANK_RB = 'BTN360CS-5500'; // Platba z účtu – Raiffeisenbank a.s.
    /** @deprecated removed in GP webpay HTTP API 1.14, use APM_CZ_MBANK */
    public const BANK_MBANK = 'BTN360CS-6210'; // Platba z účtu – mBank S.A.
    /** @deprecated removed in GP webpay HTTP API 1.14 */
    public const BANK_SBERBANK = 'BTN360CS-6800'; // Platba z účtu – Sberbank CZ, a.s.
}
