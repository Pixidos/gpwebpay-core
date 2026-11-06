.. _installation:

========================
Installing GPWebPay Core
========================

.. _installation.requirements:

Requirements
############

GPWebPay Core |version| requires PHP ^8.1 and is tested with PHP 8.1 to 8.5.
Use a PHP version that still receives security support.

GPWebPay Core requires the `ext-openssl <http://php.net/manual/en/openssl.setup.php>`_
extension.


.. _installation.composer:

Composer
########

| Simple add a dependency on``pixidos/gpwebpay-core`` to your project's ``composer.json`` file.
| If you use `Composer <https://getcomposer.org/>`_ to manage the dependencies of your project:

.. code-block:: bash

    composer require pixidos/gpwebpay-core
