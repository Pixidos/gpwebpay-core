# GPWebPay Core library
![](https://img.shields.io/badge/PHPStan-enabled-brightgreen.svg?style=flat)
[![Code Analyse](https://github.com/Pixidos/gpwebpay-core/actions/workflows/code_analyse.yml/badge.svg?branch=main)](https://github.com/Pixidos/gpwebpay-core/actions/workflows/code_analyse.yml)
[![Unit Tests](https://github.com/Pixidos/gpwebpay-core/actions/workflows/unit_tests.yml/badge.svg?branch=main)](https://github.com/Pixidos/gpwebpay-core/actions/workflows/unit_tests.yml)
[![Code coverage](https://codecov.io/gh/Pixidos/gpwebpay-core/branch/main/graph/badge.svg)](https://codecov.io/gh/Pixidos/gpwebpay-core)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Pixidos/gpwebpay-core/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Pixidos/gpwebpay-core/?branch=main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/Pixidos/gpwebpay-core/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://scrutinizer-ci.com/g/Pixidos/gpwebpay-core/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Pixidos/gpwebpay-core/build-status/main)
[![Documentation Status](https://readthedocs.org/projects/gpwebpay-core/badge/?version=latest)](https://gpwebpay-core.readthedocs.io/en/latest/?badge=latest)

[![Downloads](https://poser.pugx.org/pixidos/gpwebpay-core/downloads)](https://packagist.org/packages/pixidos/gpwebpay-core)
[![Latest stable](https://img.shields.io/packagist/v/pixidos/gpwebpay-core.svg)](https://packagist.org/packages/pixidos/gpwebpay-core)
![](https://img.shields.io/badge/php-8.2%20to%208.5-777bb3.svg?logo=php&logoColor=white&labelColor=555555)

GPWebPay Core is a PHP library for the [GP webpay](https://www.gpwebpay.cz/) HTTP API.


Requirements
------------

GPWebPay Core **3.x** requires **PHP 8.2 or newer (below PHP 9)** and the OpenSSL extension (`ext-openssl`).
It is tested on PHP 8.2–8.5. Use a PHP release that receives security updates.

The 3.x series keeps the public API and payment behavior of 2.5.0; the breaking change is the removal of PHP 8.1 support.
Version 2.5.0 remains available as a previous release. Its PHP 8.1 compatibility is historical, not a recommendation
to run an unsupported PHP version or a promise of continued maintenance.



Installation
------------

Install GPWebPay Core with [Composer](https://getcomposer.org/):

```sh
$ composer require "pixidos/gpwebpay-core:^3.0"
```


Documentation
-------------

Learn more in the [documentation](https://gpwebpay-core.readthedocs.io/en/latest/).

