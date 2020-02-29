
Migration
=========

Fusio uses the Doctrine `Migrations`_ system. Fusio has basically two components
which are using the migration system. At first Fusio it self uses it to install
the internal schema and execute updates on a new release. Then it is also
possible for the API developer to use the migration system on any remote SQL
connection.

Usage
------

For example to install Fusio you execute the following command:

.. code-block:: text
    
    php /bin/fusio migration:migrate

which then executes all migrations on the database which was configured at the
``.env`` file. You may have also executed the ``install`` command but this is
simply an alias to the ``migrate`` command.

To use the migration in your app you can provide a connection option which tells
Fusio which connection should be used. This can be any SQL connection which
you have configured at Fusio.

.. code-block:: text
    
    php /bin/fusio migration:migrate --connection=System

Fusio will look into the ``src/Migrations/`` folder and search for a folder
which has the name of your connection. In this case it would be "System".
Then it will execute all migrations which are not already executed.

Commands
--------

Fusio supports the following migration commands:

.. code-block:: text
    
    migration:execute     Execute a single migration version up or down manually.
    migration:generate    Generate a blank migration class.
    migration:latest      Outputs the latest version number
    migration:migrate     [install] Execute a migration to a specified version or the latest available version.
    migration:status      View the status of a set of migrations.
    migration:up-to-date  Tells you if your schema is up-to-date.
    migration:version     Manually add and delete migration versions from the version table.


.. _Migrations: https://github.com/doctrine/migrations/


