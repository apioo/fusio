
Select
======

Routes
------

``resources/routes.yaml``

.. code-block:: yaml

    "/test":
      version: 1
      methods:
        GET:
          public: true
          action: "${dir.src}/sql-select.php"

Connection
----------

``resources/connections.yaml``

.. code-block:: yaml

    Database-Connection:
      class: Fusio\Adapter\Sql\Connection\Sql
      config:
        type: "pdo_mysql"
        host: "127.0.0.1"
        username: "app"
        password: "secret"
        database: "app"

Action
------

``src/sql-select.php``

.. literalinclude:: ../src/sql-select.php
   :language: php

