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

use Grifart\Enum\MissingValueDeclarationException;
use Pixidos\GPWebPay\Config\PaymentConfigProvider;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Data\ResponseInterface;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Exceptions\InvalidResponseException;
use Pixidos\GPWebPay\Param\ResponseParam;
use Pixidos\GPWebPay\Param\Utils\MdParser;

class ResponseFactory
{
    /**
     * Optional signed fields passed to the Response constructor. OPERATION, ORDERNUMBER, PRCODE
     * and SRCODE are mandatory and must have a valid, non-empty value.
     */
    private const OPTIONAL_CONSTRUCTOR_PARAMS = [
        Param::MERORDERNUM,
        Param::MD,
        ResponseInterface::RESULTTEXT,
    ];

    /**
     * Other optional signed response fields. Every field is kept exactly as received, also when
     * empty, and an absent field stays absent. Any other received field is not part of
     * a GP webpay response and is ignored.
     */
    private const OPTIONAL_SIGNED_PARAMS = [
        Param::USERPARAM,
        Param::ADDINFO,
        Param::TOKEN,
        Param::PANPATTERN,
        ...ResponseInterface::RESPONSE_PARAMS,
    ];

    public function __construct(
        private readonly PaymentConfigProvider $configProvider
    ) {
    }

    /**
     * Creates a response from the fields GP webpay sent to the response URL: the query
     * parameters of a GET request or the form fields of a POST request. Pass only the data
     * of the actual request method, never both merged.
     *
     * The response is not verified here. Pass it to ResponseProviderInterface::provide()
     * or verifyPaymentResponse() before trusting any of its values.
     *
     * @param array<array-key, mixed> $params
     *
     * @throws InvalidResponseException when the data cannot be a GP webpay response for this configuration
     */
    public function create(array $params): ResponseInterface
    {
        $md = $this->getString(Param::MD, $params);
        $gateway = $this->resolveGateway($md);

        try {
            $response = new Response(
                $this->getRequiredString(Param::OPERATION, $params),
                $this->getRequiredString(Param::ORDERNUMBER, $params),
                $this->getString(Param::MERORDERNUM, $params),
                $md,
                $this->getCode(ResponseInterface::PRCODE, $params),
                $this->getCode(ResponseInterface::SRCODE, $params),
                $this->getString(ResponseInterface::RESULTTEXT, $params),
                $this->getString(Param::DIGEST, $params),
                $this->getString(ResponseInterface::DIGEST1, $params),
                $gateway
            );
        } catch (InvalidArgumentException | MissingValueDeclarationException $exception) {
            throw new InvalidResponseException(
                sprintf('Invalid GP webpay response: %s', $exception->getMessage()),
                0,
                $exception
            );
        }

        // Response treats an empty MERORDERNUM, MD or RESULTTEXT as not received. GP webpay signs the
        // fields it actually sends, so a field received with an empty value stays a signed field.
        foreach (self::OPTIONAL_CONSTRUCTOR_PARAMS as $name) {
            if (array_key_exists($name, $params) && '' === $this->getString($name, $params)) {
                $response->addParam(new ResponseParam('', $name, false));
            }
        }

        foreach (self::OPTIONAL_SIGNED_PARAMS as $name) {
            if (array_key_exists($name, $params)) {
                $response->addParam(new ResponseParam($this->getString($name, $params), $name, false));
            }
        }

        $response->sortParams();

        return $response;
    }

    /**
     * @throws InvalidResponseException
     */
    private function resolveGateway(string $md): string
    {
        $gateway = MdParser::getGatewayKey($md);
        if ('' === $gateway) {
            return $this->configProvider->getDefaultGateway();
        }

        try {
            return $this->configProvider->getGateway($gateway);
        } catch (InvalidArgumentException $exception) {
            throw new InvalidResponseException(
                'Invalid GP webpay response: MD selects a gateway that is not configured.',
                0,
                $exception
            );
        }
    }

    /**
     * @param array<array-key, mixed> $params
     *
     * @throws InvalidResponseException
     */
    private function getString(string $name, array $params): string
    {
        $value = $params[$name] ?? '';
        if (!is_string($value)) {
            throw new InvalidResponseException(
                sprintf('Invalid GP webpay response: %s must be a string, %s given.', $name, get_debug_type($value))
            );
        }

        return $value;
    }

    /**
     * @param array<array-key, mixed> $params
     *
     * @throws InvalidResponseException
     */
    private function getRequiredString(string $name, array $params): string
    {
        if (!array_key_exists($name, $params)) {
            throw new InvalidResponseException(sprintf('Invalid GP webpay response: %s is missing.', $name));
        }

        return $this->getString($name, $params);
    }

    /**
     * Return codes are signed as received, so only canonical decimal numbers are accepted.
     *
     * @param array<array-key, mixed> $params
     *
     * @throws InvalidResponseException
     */
    private function getCode(string $name, array $params): int
    {
        $value = $params[$name] ?? null;
        if (is_int($value) && $value >= 0) {
            return $value;
        }

        $value = $this->getRequiredString($name, $params);
        if (1 !== preg_match('#^(?:0|[1-9]\d{0,8})$#D', $value)) {
            throw new InvalidResponseException(
                sprintf('Invalid GP webpay response: %s must be a non-negative integer.', $name)
            );
        }

        return (int)$value;
    }
}
