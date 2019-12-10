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

namespace Pixidos\GPWebPay\Data;

use Pixidos\GPWebPay\Enum;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Exceptions\InvalidArgumentException;
use Pixidos\GPWebPay\Param\Amount;
use Pixidos\GPWebPay\Param\Currency;
use Pixidos\GPWebPay\Param\Description;
use Pixidos\GPWebPay\Param\DisablePayMethod;
use Pixidos\GPWebPay\Param\Email;
use Pixidos\GPWebPay\Param\FastPayId;
use Pixidos\GPWebPay\Param\IParam;
use Pixidos\GPWebPay\Param\Lang;
use Pixidos\GPWebPay\Param\Md;
use Pixidos\GPWebPay\Param\MerOrderNum;
use Pixidos\GPWebPay\Param\Operation as OperationParam;
use Pixidos\GPWebPay\Param\OrderNumber;
use Pixidos\GPWebPay\Param\PayMethod;
use Pixidos\GPWebPay\Param\PayMethods;
use Pixidos\GPWebPay\Param\ReferenceNumber;
use Pixidos\GPWebPay\Param\ResponseUrl;
use Pixidos\GPWebPay\Param\UserParam;
use UnexpectedValueException;

/**
 * Class Operation
 * @package Pixidos\GPWebPay
 * @author Ondra Votava <ondra@votava.it>
 */
class Operation implements IOperation
{
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\Currency::EUR
     */
    public const EUR = 978;
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\Currency::CZK
     */
    public const CZK = 203;
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\PayMethod:CARD
     */
    public const PAYMENT_CARD = 'CRD';
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\PayMethod::MASTERCARD_MOBILE
     */
    public const PAYMENT_MASTERCARD_MOBILE = 'MCM';
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\PayMethod::MASTERPASS
     */
    public const PAYMENT_MASTERPASS = 'MPS';
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\PayMethod::PLATBA24
     */
    public const PAYMENT_PLATBA24 = 'BTNCS';
    /**
     * @deprecated use \Pixidos\GPWebPay\Enum\PayMethod::GOOGLE_PAY
     */
    public const PAYMENT_GOOGLE_PAY = 'GPAY';


    /**
     * @var string|null $gatewayKey
     */
    private $gatewayKey;

    /**
     * @var IParam[]
     */
    private $params = [];


    /**
     * Operation constructor.
     *
     * @param OrderNumber|string $orderNumber max. length is 15
     * @param Amount|int|float $amount
     * @param Currency|int $currency max. length is 3
     * @param null|string $gatewayKey
     * @param null|ResponseUrl|string $responseUrl
     * @param bool $converToPennies
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    public function __construct(
        $orderNumber,
        $amount,
        $currency,
        ?string $gatewayKey = null,
        $responseUrl = null,
        bool $converToPennies = true
    ) {
        $this->addParam(new OperationParam(Enum\Operation::CREATE_ORDER()));
        $this->processConstruction($orderNumber, $amount, $currency, $gatewayKey, $responseUrl, $converToPennies);
    }

    /**
     * @return string
     * @deprecated use getParam(Param::ORDERNUMBER())
     */
    public function getOrderNumber(): string
    {
        return (string)$this->getParamStringValue(__METHOD__, Param::ORDERNUMBER);
    }

    /**
     * @return int
     * @deprecated use getParam(Param::AMOUNT())
     */
    public function getAmount(): int
    {
        return (int)$this->getParamIntValue(__METHOD__, Param::AMOUNT);
    }

    /**
     * @return int
     * @deprecated use getParam(Param::CURRENCY())
     */
    public function getCurrency(): int
    {
        return $this->getParamIntValue(__METHOD__, Param::CURRENCY) ?? self::CZK;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::RESPONSE_URL())
     */
    public function getResponseUrl(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::RESPONSE_URL);
    }

    /**
     * @param string $url max. lenght is 300
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(ResponseUrl $param)
     */
    public function setResponseUrl(string $url): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new ResponseUrl($url));

        return $this;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::MD())
     */
    public function getMd(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::MD);
    }

    /**
     * @param string $md max. length is 250!
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(ResponseUrl $param)
     */
    public function setMd(string $md): IOperation
    {
        $this->triggerError(__METHOD__);

        $this->addParam(new Md($md));

        return $this;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::DESCRIPTION())
     */
    public function getDescription(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::DESCRIPTION);
    }

    /**
     * @param string $description max. length is 255
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(Description $param)
     */
    public function setDescription(string $description): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new Description($description));

        return $this;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::MERORDERNUM())
     */
    public function getMerOrderNum(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::MERORDERNUM);
    }

    /**
     * @param string $merordernum max. length is 30
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(ResponseUrl $param)
     */
    public function setMerOrderNum(string $merordernum): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new MerOrderNum($merordernum));

        return $this;
    }

    /**
     * @return null|string
     */
    public function getGatewayKey(): ?string
    {
        return $this->gatewayKey;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::LANG())
     */
    public function getLang(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::LANG);
    }

    /**
     *
     * @param string $lang max. length is 2
     *
     * @return IOperation
     * @throws InvalidArgumentException
     */
    public function setLang(string $lang): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new Lang($lang));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::USERPARAM())
     */
    public function getUserParam1(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::USERPARAM);
    }

    /**
     * @param string $userParam max. length is 255
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new UserParam($userParam1))
     */
    public function setUserParam1(string $userParam): IOperation
    {
        $this->triggerError(__METHOD__);

        $this->addParam(new UserParam($userParam));

        return $this;
    }

    /**
     * @return null|string
     * @deprecated use getParam(Param::PAYMETHOD())
     */
    public function getPayMethod(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::PAYMETHOD);
    }

    /**
     * @param string $payMethod supported val: Operation::PAYMENT_xxx
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new PayMethod(Enum\PayMethod::$payMethod()))
     */
    public function setPayMethod(string $payMethod): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new PayMethod(Enum\PayMethod::$payMethod()));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::DISABLEPAYMETHOD())
     */
    public function getDisablePayMethod(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::DISABLEPAYMETHOD);
    }

    /**
     * Supported Values:
     * CRD – payment card
     * MCM – MasterCard Mobile
     * MPS – MasterPass
     * BTNCS - PLATBA 24
     * GPAY - Google Pay
     *
     * @param string $payMethod supported val: Enum\PayMethod::::PAYMENT_xxx
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * use addParam(new DisablePayMethod(Enum\PayMethod::$payMethod()))
     */
    public function setDisablePayMethod(string $payMethod): IOperation
    {

        $this->triggerError(__METHOD__);
        $this->addParam(new DisablePayMethod(Enum\PayMethod::$payMethod()));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::PAYMETHODS())
     */
    public function getPayMethods(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::PAYMETHODS);
    }

    /**
     * List of allowed payment methods.
     * Supported Values:
     * CRD – payment card
     * MCM – MasterCard Mobile
     * MPS – MasterPass
     * BTNCS - PLATBA 24
     * GPAY - Google Pay
     *
     * @param array $payMethods supported val: [CRD, MCM, MPS, BTNCS, GPAY]
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new PayMethods(...$methods))
     */
    public function setPayMethods(array $payMethods): IOperation
    {
        $this->triggerError(__METHOD__);
        $methods = [];
        foreach ($payMethods as $method) {
            $method = strtoupper($method);
            $methods = Enum\PayMethod::$method();
        }

        $this->addParam(new PayMethods(...$methods));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::EMAIL());
     */
    public function getEmail(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::EMAIL);
    }

    /**
     * @param string $email max. lenght is 255
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new Email($email))
     */
    public function setEmail(string $email): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new Email($email));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::REFERENCENUMBER());
     */
    public function getReferenceNumber(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::REFERENCENUMBER);
    }

    /**
     * @param string $referenceNumber max. lenght is 20
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new ReferenceNumber($referenceNumber))
     */
    public function setReferenceNumber(string $referenceNumber): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new ReferenceNumber($referenceNumber));

        return $this;
    }

    /**
     * @return string|null
     * @deprecated use getParam(Param::FASTPAYID());
     */
    public function getFastPayId(): ?string
    {
        return $this->getParamStringValue(__METHOD__, Param::FASTPAYID);
    }

    /**
     * @param int|float|string $fastPayId max. lenght is 15 and can contain only numbers
     *
     * @return IOperation
     * @throws InvalidArgumentException
     * @deprecated use addParam(new FastPayId($fastPayId))
     *
     */
    public function setFastPayId($fastPayId): IOperation
    {
        $this->triggerError(__METHOD__);
        $this->addParam(new FastPayId($fastPayId));

        return $this;
    }

    /**
     * @param IParam $param
     *
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addParam(IParam $param): IOperation
    {
        if (($param instanceof Md) && $this->gatewayKey !== (string)$param) {
            $param = new Md($this->gatewayKey . '|' . $param);
        }

        $this->params[$param->getParamName()] = $param;

        return $this;
    }

    /**
     * @param Param $param
     *
     * @return IParam|null
     */
    public function getParam(Param $param): ?IParam
    {
        return $this->params[(string)$param] ?? null;
    }

    /**
     * @return IParam[]
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     *
     * @param OrderNumber|string $orderNumber max. length is 15
     * @param Amount|int|float $amount
     * @param Currency|int $currency max. length is 3
     * @param null|string $gatewayKey
     * @param null|ResponseUrl|string $responseUrl
     * @param bool $converToPennies
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     */
    private function processConstruction(
        $orderNumber,
        $amount,
        $currency,
        ?string $gatewayKey = null,
        $responseUrl = null,
        bool $converToPennies = true
    ): void {

        if (!($amount instanceof Amount)) {
            trigger_error(
                sprintf(
                    'Use scalar value instead of %s is depracated and support will be removed in next major version',
                    Amount::class
                ),
                E_USER_DEPRECATED
            );
            $amount = new Amount($amount, $converToPennies);
        }

        if (!($orderNumber instanceof OrderNumber)) {
            trigger_error(
                sprintf(
                    'Use scalar value instead of %s is depracated and support will be removed in next major version',
                    OrderNumber::class
                ),
                E_USER_DEPRECATED
            );
            $orderNumber = new OrderNumber($orderNumber);
        }
        if (!($currency instanceof Currency)) {
            trigger_error(
                sprintf(
                    'Use scalar value instead of %s is depracated and support will be removed in next major version',
                    Currency::class
                ),
                E_USER_DEPRECATED
            );
            $currency = new Currency(Enum\Currency::fromScalar($currency));
        }

        $this->addParam($amount);
        $this->addParam($orderNumber);
        $this->addParam($currency);

        if ($gatewayKey !== null) {
            $this->gatewayKey = $gatewayKey;
            $this->addParam(new Md($gatewayKey));
        }

        if ($responseUrl !== null) {
            if (!($responseUrl instanceof ResponseUrl)) {
                trigger_error(
                    sprintf(
                        'Use scalar value instead of %s is depracated and support will be removed in next major version',
                        ResponseUrl::class
                    ),
                    E_USER_DEPRECATED
                );
                $responseUrl = new ResponseUrl($responseUrl);
            }
            $this->addParam($responseUrl);
        }
    }

    /**
     * @param string $method
     * @param string $type
     */
    private function triggerError(string $method, string $type = 'addParam(IParam $param)'): void
    {
        trigger_error(
            sprintf(
                'Use %s::%s instead of %s. Method support will be removed in next major version',
                static::class,
                $type,
                $method
            ),
            E_USER_DEPRECATED
        );
    }


    /**
     * @param string $method
     * @param string $key
     *
     * @return string|null
     */
    private function getParamStringValue(string $method, string $key): ?string
    {
        $paramValue = $this->getParamValue($method, $key);

        return $paramValue !== null ? (string)$paramValue : null;
    }

    /**
     * @param string $method
     * @param string $key
     *
     * @return int|null
     */
    private function getParamIntValue(string $method, string $key): ?int
    {
        /** @var IParam|null $paramValue */
        $paramValue = $this->getParamValue($method, $key);

        return $paramValue !== null ? (int)$paramValue->getValue() : null;
    }

    /**
     * @param string $method
     * @param string $key
     *
     *
     * @return IParam|null
     */
    private function getParamValue(string $method, string $key): ?IParam
    {
        $this->triggerError($method, "getParam(Param::$key())");

        $param = $this->getParam(Param::$key());
        if ($param === null) {
            return null;
        }

        return $param;
    }
}
