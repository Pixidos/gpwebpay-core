.. _changelog:

=========
Changelog
=========

Version 2.5.0
#############

Fixes:

* Responses with ACRC, RRN, PAR or TRACEID, and responses with USERPARAM1 (API before 1.17), now pass signature
  verification. ``ResponseFactory`` retains the signed response fields exactly as received for subsequent verification
  by ``ResponseProvider`` and ignores fields that are not part of a GP webpay response.
* VRCODE and PANPATTERN are included in the request signature.
* A request for a non-default gateway uses the response URL of that gateway.
* MD with the ``#ID=…`` or ``#IDPP=…`` suffix appended by GP webpay selects the right gateway. ``getMd()`` returns the
  merchant data without the suffix, including when the payload equals the gateway name; ``Response::getRawMd()`` returns the
  signed value.
* ``Amount(19.99)`` sends 1999 instead of 1998; values with more than two decimals or more than 15 digits are rejected.
* Gateway keys are case-insensitive everywhere (configuration, operation, response).
* PRCODE 1000 is translated as "Technical problem" instead of a single character.

Changes:

* ``ResponseFactory::create()`` throws ``InvalidResponseException`` (a ``GPWebPayException``) for missing or malformed
  fields, array values and an unknown gateway in MD, instead of enum, type or configuration errors. OPERATION,
  ORDERNUMBER, PRCODE and SRCODE are required.
* ``AmountInPennies`` rejects negative amounts and amounts longer than 15 digits.
* Gateway keys containing ``|``, ``#`` or non-ASCII characters are rejected by ``ConfigFactory``.
* Gateway getters return lowercase keys. ``getParam('MD')`` returns a ``ResponseParam`` implementing ``IParam``
  instead of an ``Md`` instance, preserving the full signed value; use ``getMd()`` for the merchant payload.
* New ``PayMethod`` values from HTTP API 1.19: PayPal, Click to Pay, GPE APM methods and Twisto. Values removed by
  GP webpay are deprecated; ``DisablePayMethod`` is deprecated in favour of ``PayMethods``.
* Documentation and examples: GET and POST responses, failure handling, idempotent processing, unique payment
  attempt numbers, a working POST form, PSD2 cardholder data in ADDINFO.
* Development: PHPUnit 9.6 and PHP_CodeSniffer 3.13 security updates, PHP 8.5 in CI, ``composer audit`` in CI.


.. versionchanged:: 2.0.0

Configuration refactoring

Breaking changes:

* Removed ``Provider``; use ``RequestFactory`` instead.
* Changed the configuration signature; see the configuration documentation.
* Renamed interfaces from ``I<name>`` to ``<name>Interface``.

New features:

* Added ``ResponseProvider``.
