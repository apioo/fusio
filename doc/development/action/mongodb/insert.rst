
Insert
======

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test":
      version: 1
      methods:
        POST:
          public: true
          action: "${dir.src}/mongodb-insert.php"

Connection
----------

``resources/connections.yaml``

.. code-block:: yaml

    Mongodb-Connection:
      class: Fusio\Adapter\Mongodb\Connection\MongoDB
      config:
        url: "mongodb://127.0.0.1"
        database: "app"

Action
------

``src/mongodb-insert.php``

.. literalinclude:: ../src/mongodb-insert.php
   :language: php

