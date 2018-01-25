
Update
======

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test/:id":
      version: 1
      methods:
        PUT:
          public: true
          action: "${dir.src}/mongodb-update.php"

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

``src/mongodb-update.php``

.. literalinclude:: ../src/mongodb-update.php
   :language: php

