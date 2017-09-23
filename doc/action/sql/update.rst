
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
          action: "${dir.src}/sql-update.php"

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

``src/sql-update.php``

.. literalinclude:: ../src/sql-update.php
   :language: php
