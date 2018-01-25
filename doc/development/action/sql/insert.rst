
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
          action: "${dir.src}/sql-insert.php"

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

``src/sql-insert.php``

.. literalinclude:: ../src/sql-insert.php
   :language: php

