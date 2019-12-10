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

namespace Pixidos\GPWebPay\Enum;

use Grifart\Enum\AutoInstances;
use Grifart\Enum\Enum;

/**
 * Class Currency
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 *
 * @method static Currency AFN()
 * @method static Currency EUR()
 * @method static Currency ALL()
 * @method static Currency DZD()
 * @method static Currency USD()
 * @method static Currency AOA()
 * @method static Currency XCD()
 * @method static Currency ARS()
 * @method static Currency AMD()
 * @method static Currency AWG()
 * @method static Currency AUD()
 * @method static Currency AZN()
 * @method static Currency BSD()
 * @method static Currency BHD()
 * @method static Currency BDT()
 * @method static Currency BBD()
 * @method static Currency BYN()
 * @method static Currency BZD()
 * @method static Currency XOF()
 * @method static Currency BMD()
 * @method static Currency INR()
 * @method static Currency BTN()
 * @method static Currency BOB()
 * @method static Currency BOV()
 * @method static Currency BAM()
 * @method static Currency BWP()
 * @method static Currency NOK()
 * @method static Currency BRL()
 * @method static Currency BND()
 * @method static Currency BGN()
 * @method static Currency BIF()
 * @method static Currency CVE()
 * @method static Currency KHR()
 * @method static Currency XAF()
 * @method static Currency CAD()
 * @method static Currency KYD()
 * @method static Currency CLP()
 * @method static Currency CLF()
 * @method static Currency CNY()
 * @method static Currency COP()
 * @method static Currency COU()
 * @method static Currency KMF()
 * @method static Currency CDF()
 * @method static Currency NZD()
 * @method static Currency CRC()
 * @method static Currency HRK()
 * @method static Currency CUP()
 * @method static Currency CUC()
 * @method static Currency ANG()
 * @method static Currency CZK()
 * @method static Currency DKK()
 * @method static Currency DJF()
 * @method static Currency DOP()
 * @method static Currency EGP()
 * @method static Currency SVC()
 * @method static Currency ERN()
 * @method static Currency ETB()
 * @method static Currency FKP()
 * @method static Currency FJD()
 * @method static Currency XPF()
 * @method static Currency GMD()
 * @method static Currency GEL()
 * @method static Currency GHS()
 * @method static Currency GIP()
 * @method static Currency GTQ()
 * @method static Currency GBP()
 * @method static Currency GNF()
 * @method static Currency GYD()
 * @method static Currency HTG()
 * @method static Currency HNL()
 * @method static Currency HKD()
 * @method static Currency HUF()
 * @method static Currency ISK()
 * @method static Currency IDR()
 * @method static Currency XDR()
 * @method static Currency IRR()
 * @method static Currency IQD()
 * @method static Currency ILS()
 * @method static Currency JMD()
 * @method static Currency JPY()
 * @method static Currency JOD()
 * @method static Currency KZT()
 * @method static Currency KES()
 * @method static Currency KPW()
 * @method static Currency KRW()
 * @method static Currency KWD()
 * @method static Currency KGS()
 * @method static Currency LAK()
 * @method static Currency LBP()
 * @method static Currency LSL()
 * @method static Currency ZAR()
 * @method static Currency LRD()
 * @method static Currency LYD()
 * @method static Currency CHF()
 * @method static Currency MOP()
 * @method static Currency MKD()
 * @method static Currency MGA()
 * @method static Currency MWK()
 * @method static Currency MYR()
 * @method static Currency MVR()
 * @method static Currency MRU()
 * @method static Currency MUR()
 * @method static Currency XUA()
 * @method static Currency MXN()
 * @method static Currency MXV()
 * @method static Currency MDL()
 * @method static Currency MNT()
 * @method static Currency MAD()
 * @method static Currency MZN()
 * @method static Currency MMK()
 * @method static Currency NAD()
 * @method static Currency NPR()
 * @method static Currency NIO()
 * @method static Currency NGN()
 * @method static Currency OMR()
 * @method static Currency PKR()
 * @method static Currency PAB()
 * @method static Currency PGK()
 * @method static Currency PYG()
 * @method static Currency PEN()
 * @method static Currency PHP()
 * @method static Currency PLN()
 * @method static Currency QAR()
 * @method static Currency RON()
 * @method static Currency RUB()
 * @method static Currency RWF()
 * @method static Currency SHP()
 * @method static Currency WST()
 * @method static Currency STN()
 * @method static Currency SAR()
 * @method static Currency RSD()
 * @method static Currency SCR()
 * @method static Currency SLL()
 * @method static Currency SGD()
 * @method static Currency XSU()
 * @method static Currency SBD()
 * @method static Currency SOS()
 * @method static Currency SSP()
 * @method static Currency LKR()
 * @method static Currency SDG()
 * @method static Currency SRD()
 * @method static Currency SZL()
 * @method static Currency SEK()
 * @method static Currency CHE()
 * @method static Currency CHW()
 * @method static Currency SYP()
 * @method static Currency TWD()
 * @method static Currency TJS()
 * @method static Currency TZS()
 * @method static Currency THB()
 * @method static Currency TOP()
 * @method static Currency TTD()
 * @method static Currency TND()
 * @method static Currency TRY()
 * @method static Currency TMT()
 * @method static Currency UGX()
 * @method static Currency UAH()
 * @method static Currency AED()
 * @method static Currency USN()
 * @method static Currency UYU()
 * @method static Currency UYI()
 * @method static Currency UYW()
 * @method static Currency UZS()
 * @method static Currency VUV()
 * @method static Currency VES()
 * @method static Currency VND()
 * @method static Currency YER()
 * @method static Currency ZMW()
 * @method static Currency ZWL()
 * @method static Currency XBA()
 * @method static Currency XBB()
 * @method static Currency XBC()
 * @method static Currency XBD()
 * @method static Currency XTS()
 * @method static Currency XXX()
 * @method static Currency XAU()
 * @method static Currency XPD()
 * @method static Currency XPT()
 * @method static Currency XAG()
 */
final class Currency extends Enum
{

    use AutoInstances;

    public const AFN = '971'; // Afghani
    public const EUR = '978'; // Euro
    public const ALL = '008'; // Lek
    public const DZD = '012'; // Algerian Dinar
    public const USD = '840'; // US Dollar
    public const AOA = '973'; // Kwanza
    public const XCD = '951'; // East Caribbean Dollar
    public const ARS = '032'; // Argentine Peso
    public const AMD = '051'; // Armenian Dram
    public const AWG = '533'; // Aruban Florin
    public const AUD = '036'; // Australian Dollar
    public const AZN = '944'; // Azerbaijan Manat
    public const BSD = '044'; // Bahamian Dollar
    public const BHD = '048'; // Bahraini Dinar
    public const BDT = '050'; // Taka
    public const BBD = '052'; // Barbados Dollar
    public const BYN = '933'; // Belarusian Ruble
    public const BZD = '084'; // Belize Dollar
    public const XOF = '952'; // CFA Franc BCEAO
    public const BMD = '060'; // Bermudian Dollar
    public const INR = '356'; // Indian Rupee
    public const BTN = '064'; // Ngultrum
    public const BOB = '068'; // Boliviano
    public const BOV = '984'; // Mvdol
    public const BAM = '977'; // Convertible Mark
    public const BWP = '072'; // Pula
    public const NOK = '578'; // Norwegian Krone
    public const BRL = '986'; // Brazilian Real
    public const BND = '096'; // Brunei Dollar
    public const BGN = '975'; // Bulgarian Lev
    public const BIF = '108'; // Burundi Franc
    public const CVE = '132'; // Cabo Verde Escudo
    public const KHR = '116'; // Riel
    public const XAF = '950'; // CFA Franc BEAC
    public const CAD = '124'; // Canadian Dollar
    public const KYD = '136'; // Cayman Islands Dollar
    public const CLP = '152'; // Chilean Peso
    public const CLF = '990'; // Unidad de Fomento
    public const CNY = '156'; // Yuan Renminbi
    public const COP = '170'; // Colombian Peso
    public const COU = '970'; // Unidad de Valor Real
    public const KMF = '174'; // Comorian Franc
    public const CDF = '976'; // Congolese Franc
    public const NZD = '554'; // New Zealand Dollar
    public const CRC = '188'; // Costa Rican Colon
    public const HRK = '191'; // Kuna
    public const CUP = '192'; // Cuban Peso
    public const CUC = '931'; // Peso Convertible
    public const ANG = '532'; // Netherlands Antillean Guilder
    public const CZK = '203'; // Czech Koruna
    public const DKK = '208'; // Danish Krone
    public const DJF = '262'; // Djibouti Franc
    public const DOP = '214'; // Dominican Peso
    public const EGP = '818'; // Egyptian Pound
    public const SVC = '222'; // El Salvador Colon
    public const ERN = '232'; // Nakfa
    public const ETB = '230'; // Ethiopian Birr
    public const FKP = '238'; // Falkland Islands Pound
    public const FJD = '242'; // Fiji Dollar
    public const XPF = '953'; // CFP Franc
    public const GMD = '270'; // Dalasi
    public const GEL = '981'; // Lari
    public const GHS = '936'; // Ghana Cedi
    public const GIP = '292'; // Gibraltar Pound
    public const GTQ = '320'; // Quetzal
    public const GBP = '826'; // Pound Sterling
    public const GNF = '324'; // Guinean Franc
    public const GYD = '328'; // Guyana Dollar
    public const HTG = '332'; // Gourde
    public const HNL = '340'; // Lempira
    public const HKD = '344'; // Hong Kong Dollar
    public const HUF = '348'; // Forint
    public const ISK = '352'; // Iceland Krona
    public const IDR = '360'; // Rupiah
    public const XDR = '960'; // SDR (Special Drawing Right)
    public const IRR = '364'; // Iranian Rial
    public const IQD = '368'; // Iraqi Dinar
    public const ILS = '376'; // New Israeli Sheqel
    public const JMD = '388'; // Jamaican Dollar
    public const JPY = '392'; // Yen
    public const JOD = '400'; // Jordanian Dinar
    public const KZT = '398'; // Tenge
    public const KES = '404'; // Kenyan Shilling
    public const KPW = '408'; // North Korean Won
    public const KRW = '410'; // Won
    public const KWD = '414'; // Kuwaiti Dinar
    public const KGS = '417'; // Som
    public const LAK = '418'; // Lao Kip
    public const LBP = '422'; // Lebanese Pound
    public const LSL = '426'; // Loti
    public const ZAR = '710'; // Rand
    public const LRD = '430'; // Liberian Dollar
    public const LYD = '434'; // Libyan Dinar
    public const CHF = '756'; // Swiss Franc
    public const MOP = '446'; // Pataca
    public const MKD = '807'; // Denar
    public const MGA = '969'; // Malagasy Ariary
    public const MWK = '454'; // Malawi Kwacha
    public const MYR = '458'; // Malaysian Ringgit
    public const MVR = '462'; // Rufiyaa
    public const MRU = '929'; // Ouguiya
    public const MUR = '480'; // Mauritius Rupee
    public const XUA = '965'; // ADB Unit of Account
    public const MXN = '484'; // Mexican Peso
    public const MXV = '979'; // Mexican Unidad de Inversion (UDI)
    public const MDL = '498'; // Moldovan Leu
    public const MNT = '496'; // Tugrik
    public const MAD = '504'; // Moroccan Dirham
    public const MZN = '943'; // Mozambique Metical
    public const MMK = '104'; // Kyat
    public const NAD = '516'; // Namibia Dollar
    public const NPR = '524'; // Nepalese Rupee
    public const NIO = '558'; // Cordoba Oro
    public const NGN = '566'; // Naira
    public const OMR = '512'; // Rial Omani
    public const PKR = '586'; // Pakistan Rupee
    public const PAB = '590'; // Balboa
    public const PGK = '598'; // Kina
    public const PYG = '600'; // Guarani
    public const PEN = '604'; // Sol
    public const PHP = '608'; // Philippine Peso
    public const PLN = '985'; // Zloty
    public const QAR = '634'; // Qatari Rial
    public const RON = '946'; // Romanian Leu
    public const RUB = '643'; // Russian Ruble
    public const RWF = '646'; // Rwanda Franc
    public const SHP = '654'; // Saint Helena Pound
    public const WST = '882'; // Tala
    public const STN = '930'; // Dobra
    public const SAR = '682'; // Saudi Riyal
    public const RSD = '941'; // Serbian Dinar
    public const SCR = '690'; // Seychelles Rupee
    public const SLL = '694'; // Leone
    public const SGD = '702'; // Singapore Dollar
    public const XSU = '994'; // Sucre
    public const SBD = '090'; // Solomon Islands Dollar
    public const SOS = '706'; // Somali Shilling
    public const SSP = '728'; // South Sudanese Pound
    public const LKR = '144'; // Sri Lanka Rupee
    public const SDG = '938'; // Sudanese Pound
    public const SRD = '968'; // Surinam Dollar
    public const SZL = '748'; // Lilangeni
    public const SEK = '752'; // Swedish Krona
    public const CHE = '947'; // WIR Euro
    public const CHW = '948'; // WIR Franc
    public const SYP = '760'; // Syrian Pound
    public const TWD = '901'; // New Taiwan Dollar
    public const TJS = '972'; // Somoni
    public const TZS = '834'; // Tanzanian Shilling
    public const THB = '764'; // Baht
    public const TOP = '776'; // Pa’anga
    public const TTD = '780'; // Trinidad and Tobago Dollar
    public const TND = '788'; // Tunisian Dinar
    public const TRY = '949'; // Turkish Lira
    public const TMT = '934'; // Turkmenistan New Manat
    public const UGX = '800'; // Uganda Shilling
    public const UAH = '980'; // Hryvnia
    public const AED = '784'; // UAE Dirham
    public const USN = '997'; // US Dollar (Next day)
    public const UYU = '858'; // Peso Uruguayo
    public const UYI = '940'; // Uruguay Peso en Unidades Indexadas (UI)
    public const UYW = '927'; // Unidad Previsional
    public const UZS = '860'; // Uzbekistan Sum
    public const VUV = '548'; // Vatu
    public const VES = '928'; // Bolívar Soberano
    public const VND = '704'; // Dong
    public const YER = '886'; // Yemeni Rial
    public const ZMW = '967'; // Zambian Kwacha
    public const ZWL = '932'; // Zimbabwe Dollar
    public const XBA = '955'; // Bond Markets Unit European Composite Unit (EURCO)
    public const XBB = '956'; // Bond Markets Unit European Monetary Unit (E.M.U.-6)
    public const XBC = '957'; // Bond Markets Unit European Unit of Account 9 (E.U.A.-9)
    public const XBD = '958'; // Bond Markets Unit European Unit of Account 17 (E.U.A.-17)
    public const XTS = '963'; // Codes specifically reserved for testing purposes
    public const XXX = '999'; // The codes assigned for transactions where no currency is involved
    public const XAU = '959'; // Gold
    public const XPD = '964'; // Palladium
    public const XPT = '962'; // Platinum
    public const XAG = '961'; // Silver

}
