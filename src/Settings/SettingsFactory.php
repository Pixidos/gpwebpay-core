<?php declare(strict_types=1);

namespace Pixidos\GPWebPay\Settings;

use Pixidos\GPWebPay\Enum\DepositFlag as DepositFlagEnum;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\DepositFlag;
use Pixidos\GPWebPay\Param\MerchantNumber;
use UnexpectedValueException;
use function is_array;

class SettingsFactory implements SettingsFactoryInterface
{
    /**
     * @param array<string>|string $privateKey
     * @param array<string>|string $privateKeyPassword
     * @param array<string>|string $publicKey
     * @param array<string>|string $url
     * @param array<string>|string $merchantNumber
     * @param array<int>|int       $depositFlag
     * @param string               $defaultGatewayKey
     *
     * @return Settings
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public static function create(
        $privateKey,
        $privateKeyPassword,
        $publicKey,
        $url,
        $merchantNumber,
        $depositFlag = 1,
        string $defaultGatewayKey = 'czk'
    ): Settings {

        if (is_array($privateKey)) {
            if (!is_array($privateKeyPassword) || !is_array($merchantNumber)) {
                throw new InvalidArgumentException(
                    'Mischmath data. When you set privateKey as array you must set privateKeyPassword and merchantNumber as array too.'
                );
            }
            $settings = self::processArray($privateKey, $privateKeyPassword, $publicKey, $url, $merchantNumber, $depositFlag);

            return new Settings($defaultGatewayKey, ...$settings);
        }

        if (is_array($publicKey) || is_array($url) || is_array($depositFlag) || is_array($privateKeyPassword) || is_array($merchantNumber)) {
            throw new InvalidArgumentException(
                'Mischmath data. When you set privateKey as string you must set all other parameters as scalar too.'
            );
        }

        return new Settings(
            $defaultGatewayKey,
            self::createSetting(
                $privateKey,
                (string)$privateKeyPassword,
                $publicKey,
                $url,
                (string)$merchantNumber,
                $depositFlag,
                $defaultGatewayKey
            )
        );

    }


    /**
     * @param array                $privateKeys
     * @param array                $privateKeyPasswords
     * @param array<string>|string $publicKey
     * @param array<string>|string $url
     * @param array                $merchantNumbers
     * @param array<int>|int       $depositFlag
     *
     * @return array
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    private static function processArray(
        array $privateKeys,
        array $privateKeyPasswords,
        $publicKey,
        $url,
        array $merchantNumbers,
        $depositFlag
    ): array {
        $settings = [];
        foreach ($privateKeys as $key => $privateKey) {
            if (!array_key_exists($key, $privateKeyPasswords)) {
                throw new InvalidArgumentException(sprintf('Missing key:"%s" in privateKeyPassword configurarion', $key));
            }
            if (!array_key_exists($key, $merchantNumbers)) {
                throw new InvalidArgumentException(sprintf('Missing key:"%s" in merchantNumbers configurarion', $key));
            }

            $settings[$key] = self::createSetting(
                $privateKeys[$key],
                $privateKeyPasswords[$key],
                (string)self::getValue($key, $publicKey, 'publicKey'),
                (string)self::getValue($key, $url, 'url'),
                $merchantNumbers[$key],
                (int)self::getValue($key, $depositFlag, 'depositFlag'),
                $key
            );
        }

        return $settings;
    }


    /**
     * @param string           $key
     * @param array|int|string $data
     * @param string           $name
     *
     * @return int|string
     * @throws InvalidArgumentException
     */
    private static function getValue(string $key, $data, string $name)
    {
        if (!is_array($data)) {
            return $data;
        }
        if (!array_key_exists($key, $data)) {
            throw new InvalidArgumentException(sprintf('Missing key:"%s" in %s configurarion', $key, $name));
        }

        return $data[$key];
    }

    /**
     * @param string $privateKey
     * @param string $privateKeyPassword
     * @param string $publicKey
     * @param string $url
     * @param string $merchantNumber
     * @param int    $depositFlag
     * @param string $key
     *
     * @return Setting
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    private static function createSetting(
        string $privateKey,
        string $privateKeyPassword,
        string $publicKey,
        string $url,
        string $merchantNumber,
        int $depositFlag,
        string $key
    ): Setting {
        return new Setting(
            $privateKey,
            $privateKeyPassword,
            $publicKey,
            $url,
            new MerchantNumber($merchantNumber),
            new DepositFlag((new DepositFlagEnum($depositFlag))),
            $key
        );
    }
}
