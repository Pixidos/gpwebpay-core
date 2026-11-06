.. _example:

=====================
Example of use
=====================

These examples are part of the repository (``example/``) and run in the test suite with test keys.
They read keys and the merchant number from environment variables:

.. literalinclude:: ../example/config.php
    :language: php


.. _example.request:

Create Request and Rendering
#############################

.. literalinclude:: ../example/create_request.php
    :language: php


.. _example.response:

Handle Response from GP webpay
##############################

.. literalinclude:: ../example/handle_response.php
    :language: php


.. note::

    For more examples see **tests/WorkflowTest.php** and **tests/ExamplesTest.php**.
