
Find-One
========

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test/:id":
      version: 1
      methods:
        GET:
          public: true
          action: "${dir.src}/mongodb-find-one.php"

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

``src/mongodb-find-one.php``

.. literalinclude:: ../src/mongodb-find-one.php
   :language: php

