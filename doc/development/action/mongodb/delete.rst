
Delete
======

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test/:id":
      version: 1
      methods:
        DELETE:
          public: true
          action: "${dir.src}/mongodb-delete.php"

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

``src/mongodb-delete.php``

.. literalinclude:: ../src/mongodb-delete.php
   :language: php

