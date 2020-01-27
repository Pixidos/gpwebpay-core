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

namespace Pixidos\GPWebPay;


class_alias(\Pixidos\GPWebPay\Signer\Signer::class, 'Pixidos\GPWebPay\Signer');
if (!class_exists('Pixidos\GPWebPay\Signer')) {
    /** @deprecated */
    class Signer extends \Pixidos\GPWebPay\Signer\Signer
    {
    }
}

class_alias(\Pixidos\GPWebPay\Signer\SignerFactory::class, 'Pixidos\GPWebPay\SignerFactory');

if (!class_exists('Pixidos\GPWebPay\SignerFactory')) {
    /** @deprecated */
    class Signer extends \Pixidos\GPWebPay\Signer\SignerFactory
    {
    }
}

class_alias(\Pixidos\GPWebPay\Settings\Settings::class, 'Pixidos\GPWebPay\Settings');
if (!class_exists('Pixidos\GPWebPay\Settings')) {
    /** @deprecated */
    class Settings extends \Pixidos\GPWebPay\Settings\Settings
    {
    }
}


class_alias(\Pixidos\GPWebPay\Data\Request::class, 'Pixidos\GPWebPay\Request');
if (!class_exists('Pixidos\GPWebPay\Request')) {
    /** @deprecated */
    class Request extends \Pixidos\GPWebPay\Data\Request
    {
    }
}

class_alias(\Pixidos\GPWebPay\Data\Response::class, 'Pixidos\GPWebPay\Response');
if (!class_exists('Pixidos\GPWebPay\Response')) {
    /** @deprecated */
    class Response extends \Pixidos\GPWebPay\Data\Response
    {
    }
}

class_alias(\Pixidos\GPWebPay\Data\Operation::class, 'Pixidos\GPWebPay\Operation');
if (!class_exists('Pixidos\GPWebPay\Operation')) {
    /** @deprecated */
    class Operation extends \Pixidos\GPWebPay\Data\Operation
    {
    }
}


namespace Pixidos\GPWebPay\Interfaces;


class_alias(\Pixidos\GPWebPay\Signer\ISigner::class, 'Pixidos\GPWebPay\Intefaces\ISigner');
if (!class_exists('Pixidos\GPWebPay\Intefaces\ISigner')) {
    /** @deprecated */
    interface ISigner extends \Pixidos\GPWebPay\Signer\ISigner
    {
    }
}

class_alias(\Pixidos\GPWebPay\Signer\ISignerFactory::class, 'Pixidos\GPWebPay\Intefaces\ISignerFactory');
if (!class_exists('Pixidos\GPWebPay\Intefaces\ISignerFactory')) {
    /** @deprecated */
    interface ISignerFactory extends \Pixidos\GPWebPay\Signer\ISignerFactory
    {
    }
}

class_alias(\Pixidos\GPWebPay\IProvider::class, 'Pixidos\GPWebPay\Intefaces\IProvider');
if (!class_exists('Pixidos\GPWebPay\Intefaces\IProvider')) {
    /** @deprecated */
    interface IProvider extends \Pixidos\GPWebPay\IProvider
    {
    }
}

class_alias(\Pixidos\GPWebPay\Data\IRequest::class, 'Pixidos\GPWebPay\Intefaces\IRequest');
if (!class_exists('Pixidos\GPWebPay\Intefaces\IRequest')) {
    /** @deprecated */
    interface IRequest extends \Pixidos\GPWebPay\Data\IRequest
    {
    }
}

class_alias(\Pixidos\GPWebPay\Data\IResponse::class, 'Pixidos\GPWebPay\Intefaces\IResponse');
if (!class_exists('Pixidos\GPWebPay\Intefaces\IResponse')) {
    /** @deprecated */
    interface IResponse extends \Pixidos\GPWebPay\Data\IResponse
    {
    }
}

class_alias(\Pixidos\GPWebPay\Data\IOperation::class, 'Pixidos\GPWebPay\Intefaces\IOperation');
if (!class_exists('Pixidos\GPWebPay\Intefaces\IOperation')) {
    /** @deprecated */
    interface IOperation extends \Pixidos\GPWebPay\Data\IOperation
    {
    }
}
