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

namespace Pixidos\GPWebPay\Factory;

use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Param\ResponseParam;
use ReflectionClass;

class ResponseFactory
{
    public function __construct(
        private readonly PaymentConfigProvider $configProvider
    ) {
    }

    /**
     * @param array<string, string> $params
     */
    public function create(array $params): ResponseInterface
    {
        $md = $this->getStringValue(Param::MD, $params);
        $gateway = $this->configProvider->getDefaultGateway();

        if ('' !== $md) {
            $key = explode('|', $md, 2);
            if ('' !== $key[0]) {
                $gateway = $key[0];
            }
        }

        $response = new Response(
            $this->getStringValue(Param::OPERATION, $params),
            $this->getStringValue(Param::ORDERNUMBER, $params),
            $this->getStringValue(Param::MERORDERNUM, $params),
            $md,
            $this->getIntValue(ResponseInterface::PRCODE, $params, 1000),
            $this->getIntValue(ResponseInterface::SRCODE, $params, 0),
            $this->getStringValue(ResponseInterface::RESULTTEXT, $params),
            $this->getStringValue(Param::DIGEST, $params),
            $this->getStringValue(ResponseInterface::DIGEST1, $params),
            $gateway
        );

        $paramsKeys = array_keys((new ReflectionClass(Param::class))->getConstants());

        $paramsKeys = array_merge($paramsKeys, ResponseInterface::RESPONSE_PARAMS);

        foreach ($params as $key => $value) {
            if (in_array($key, $paramsKeys, true)) {
                $response->addParam(new ResponseParam($value, $key));
            }
        }

        $response->sortParams();

        return $response;
    }

    /**
     * @param array<string, string> $params
     */
    private function getStringValue(string $name, array &$params, string $defaultValue = ''): string
    {
        $value = $defaultValue;
        if (isset($params[$name])) {
            $value = $params[$name];
            unset($params[$name]);
        }

        return $value;
    }

    /**
     * @param array<string, string> $params
     */
    private function getIntValue(string $name, array &$params, int $defaultValue = 0): int
    {
        $value = $defaultValue;
        if (isset($params[$name])) {
            $value = $params[$name];
            unset($params[$name]);
        }

        return (int)$value;
    }
}
