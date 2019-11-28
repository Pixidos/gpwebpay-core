<?php

$xml = file_get_contents('https://www.currency-iso.org/dam/downloads/lists/list_one.xml');

/** @noinspection PhpComposerExtensionStubsInspection */
$dom = simplexml_load_string($xml);

$header = <<<HEADER
<?php declare(strict_types=1);


namespace Pixidos\GPWebPay\Enum;

use MyCLabs\Enum\Enum;


HEADER;

$docBloc = <<<DOCBLOC
/**
 * Class Currency
 * @package Pixidos\GPWebPay\Enum
 * @author Ondra Votava <ondra@votava.it>
 * \n
DOCBLOC;


$classDeffinition = <<<CDEF
final class Currency extends Enum
{


CDEF;

$currencies = [];
$constants = '';

foreach ($dom->CcyTbl->CcyNtry as $ccy) {

    $curr = (string)$ccy->Ccy;
    if ($curr !== '' && !in_array($curr, $currencies, true)) {
        $constants .= '    public const ' . $curr . ' = ' . "'$ccy->CcyNbr'" . '; // ' . $ccy->CcyNm . "\n";
        $docBloc .= ' * @method static Currency ' . $curr . "()\n";
        $currencies[] = $curr;
    }
}

$docBloc .= " */\n";

$content = $header . $docBloc . $classDeffinition . $constants;
$content .= <<<CONTENT

}


CONTENT;


$file = fopen(__DIR__ . '/src/Enum/Currency.php', 'wb');
fwrite($file, $content);
fclose($file);
