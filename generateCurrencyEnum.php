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

$xml = file_get_contents('https://www.currency-iso.org/dam/downloads/lists/list_one.xml');

/** @noinspection PhpComposerExtensionStubsInspection */
$dom = simplexml_load_string($xml);

$header = <<<HEADER
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


HEADER;

$docBloc = <<<DOCBLOC
/**
 * \n
DOCBLOC;


$classDeffinition = <<<CDEF
final class Currency extends Enum implements Stringable
{

use AutoInstances;

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
