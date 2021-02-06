<?php

/*
 * @Copyright This file is part of the Pixidos package.
 *
 *  (c) Ondra Votava <ondra@votava.dev>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

$config = [];

if (PHP_VERSION_ID >= 80000) {
    // Change of signature in PHP 8.0

    $config['parameters']['ignoreErrors'][] = [
        'message' => '~^Method Pixidos\\\\GPWebPay\\\\Signer\\\\Key\\\\PrivateKey::createKey\(\) should return resource but returns OpenSSLAsymmetricKey\.$~',
        'count' => 1,
        'path' => '../../src/Signer/Key/PrivateKey.php',
    ];

    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Method Pixidos\\\\GPWebPay\\\\Signer\\\\Key\\\\PublicKey::createKey\(\) should return resource but returns OpenSSLAsymmetricKey\.$#',
        'count' => 1,
        'path' => '../../src/Signer/Key/PublicKey.php',
    ];

    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Property Pixidos\\\\GPWebPay\\\\Signer\\\\Key\\\\AbstractKey::\$key \(resource\|null\) does not accept OpenSSLAsymmetricKey\.$#',
        'count' => 1,
        'path' => '../../src/Signer/Key/PublicKey.php',
    ];

    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Parameter \#3 \$private_key of function openssl_sign expects array\|OpenSSLAsymmetricKey\|OpenSSLCertificate\|string, resource given\.$#',
        'count' => 1,
        'path' => '../../src/Signer/Signer.php',
    ];

    $config['parameters']['ignoreErrors'][] = [
        'message' => '#^Parameter \#3 \$public_key of function openssl_verify expects array\|OpenSSLAsymmetricKey\|OpenSSLCertificate\|string, resource given\.$#',
        'count' => 1,
        'path' => '../../src/Signer/Signer.php',
    ];
}

return $config;




