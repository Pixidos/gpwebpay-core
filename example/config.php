<?php

/**
 * Configuration shared by the examples.
 *
 * Never put private keys, passphrases or merchant credentials into version control,
 * and keep key files outside the web root. These examples read them from environment variables:
 *
 *   GPWEBPAY_PRIVATE_KEY             path to your private key (PEM) generated in the GP webpay portal
 *   GPWEBPAY_PRIVATE_KEY_PASSPHRASE  passphrase of that key
 *   GPWEBPAY_PUBLIC_KEY              path to the GP webpay public key (PEM) from the portal
 *   GPWEBPAY_MERCHANT_NUMBER         your merchant number
 */

declare(strict_types=1);

use Pixidos\GPWebPay\Config\Factory\ConfigFactory;

$environment = static function (string $name): string {
    $value = getenv($name);
    if (false === $value || '' === $value) {
        throw new RuntimeException(sprintf('Set the %s environment variable.', $name));
    }

    return $value;
};

return [
    ConfigFactory::PRIVATE_KEY => $environment('GPWEBPAY_PRIVATE_KEY'),
    ConfigFactory::PRIVATE_KEY_PASSPHRASE => $environment('GPWEBPAY_PRIVATE_KEY_PASSPHRASE'),
    ConfigFactory::PUBLIC_KEY => $environment('GPWEBPAY_PUBLIC_KEY'),
    // Test environment of GP webpay HTTP API 1.19. Production: https://3dsecure.gpwebpay.com/pgw/order.do
    // Use the address your provider gives you if it differs.
    ConfigFactory::URL => 'https://test.3dsecure.gpwebpay.com/pgw/order.do',
    ConfigFactory::MERCHANT_NUMBER => $environment('GPWEBPAY_MERCHANT_NUMBER'),
    ConfigFactory::DEPOSIT_FLAG => 1,
    // Without query parameters: GP webpay may refuse return addresses with parameters.
    ConfigFactory::RESPONSE_URL => 'https://shop.example/payment/gpwebpay-response',
];
