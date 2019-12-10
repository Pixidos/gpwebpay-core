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

namespace Pixidos\GPWebPay\Factory;

use Pixidos\GPWebPay\Data\IResponse;
use Pixidos\GPWebPay\Data\Response;
use Pixidos\GPWebPay\Enum\Param;
use Pixidos\GPWebPay\Settings\Settings;

class ResponseFactory
{
    /**
     * @var Settings
     */
    private $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    public function create(array $params): IResponse
    {
        $operation = $params[Param::OPERATION] ?? '';
        $ordernumber = $params[Param::ORDERNUMBER] ?? '';
        $merordernum = $params[Param::MERORDERNUM] ?? null;
        $md = $params[Param::MD] ?? null;
        $prcode = $params[Response::PRCODE] ?? 1000;
        $srcode = $params[Response::SRCODE] ?? 0;
        $resulttext = $params[Response::RESULTTEXT] ?? null;
        $digest = $params[Param::DIGEST] ?? '';
        $digest1 = $params[Response::DIGEST_1] ?? '';

        $key = explode('|', $md, 2);

        $gatewayKey = $key[0] ?: $this->settings->getDefaultGatewayKey();

        $response = new Response(
            $operation,
            $ordernumber,
            $merordernum,
            $md,
            (int)$prcode,
            (int)$srcode,
            $resulttext,
            $digest,
            $digest1,
            $gatewayKey
        );

        if (isset($params[Param::USERPARAM])) {
            $response->setUserParam1($params[Param::USERPARAM]);
        }

        return $response;
    }
}
