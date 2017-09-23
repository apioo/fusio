
Find
====

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test":
      version: 1
      methods:
        GET:
          public: true
          action: "${dir.src}/mongodb-find.php"

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

``src/mongodb-find.php``

.. literalinclude:: ../src/mongodb-find.php
   :language: php

