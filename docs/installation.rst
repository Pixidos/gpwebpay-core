.. _installation:

========================
Installing GPWebPay Core
========================

.. _installation.requirements:

Requirements
############

GPWebPay Core 3.x requires PHP 8.2 or newer, below PHP 9 (``^8.2``), and is tested on PHP 8.2–8.5.
Use a PHP version that still receives security support.

GPWebPay Core requires the `ext-openssl <http://php.net/manual/en/openssl.setup.php>`_
extension.


.. _installation.composer:

Composer
########

Install the library with `Composer <https://getcomposer.org/>`_:

.. code-block:: bash

    composer require "pixidos/gpwebpay-core:^3.0"


Upgrading from 2.5
##################

Upgrade PHP to a supported release of 8.2 or newer, then allow ``pixidos/gpwebpay-core:^3.0`` in your project.
The public API and payment behavior are unchanged. Version 2.5.0 remains available as a previous release; its
PHP 8.1 compatibility does not mean PHP 8.1 receives security updates or that the old series has a maintenance commitment.
