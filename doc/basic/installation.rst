
Installation
============

It is possible to install Fusio either through composer or manually file 
download. Place the project into the www directory of the web server.

**Composer**

.. code-block:: text

    composer create-project fusio/fusio

**Download**

https://github.com/apioo/fusio/releases

Configuration
-------------

You can either manually install Fusio with the steps below or you can also use
the browser based installer at ``public/install.php``. Note because of security
reasons it is highly recommended to remove the installer script after the
installation.

* **Adjust the configuration file**

  Open the file ``.env`` in the Fusio directory and change the key ``FUSIO_URL``
  to the domain pointing to the public folder. Also insert the database 
  credentials to the ``FUSIO_DB_*`` keys.
* **Execute the installation command**

  The installation script inserts the Fusio database schema into the provided 
  database. It can be executed with the following command 
  ``php bin/fusio install``.
* **Create administrator user**

  After the installation is complete you have to create a new administrator 
  account. Therefor you can use the following command ``php bin/fusio adduser``. 
  Choose as account type "Administrator".

You can verify the installation by visiting the ``psx_url`` with a browser. You
should see a API response that the installation was successful. The backend is
available at ``/fusio/``.

In case you want to install Fusio on a specific database you need to adjust the
``driver`` parameter at the ``configuration.php`` file:

* ``pdo_mysql``: MySQL
* ``pdo_sqlite``: SQLite
* ``pdo_pgsql``: PostgreSQL
* ``sqlsrv``: Microsoft SQL Server
* ``oci8``: Oracle
* ``sqlanywhere``: SAP Sybase SQL Anywhere


Setup
-----

Fusio can be used on almost any setup which provides a web server and supports
PHP. Please take a look at a platform specific setup guide: 

* :doc:`/setup/apache`
* :doc:`/setup/nginx`
* :doc:`/setup/iis`
* :doc:`/setup/docker`
* :doc:`/setup/cpanel`
* :doc:`/setup/shared_hosting`

Apps
----

Backend
^^^^^^^

At the endpoint ``fusio/index.html`` you can login to the backend app. You
should be able to login with the username (which you have entered for the ``adduser``
command) and the password which you have used. The following list covers the 
most login errors in case you are not able to login at the backend:

* **The javascript Backend-App uses the wrong API endpoint**

  This can be tested with the browser developer console. If you login at the 
  backend with no credentials the app should make an request to the 
  ``/backend/token`` endpoint which should return a JSON response i.e.: 

  .. code-block:: json

      { "error": "invalid_request", "error_description": "Credentials not available" }

  If this is the case your app is correctly configured. If this is not the case 
  you need to adjust the endpoint url at ``/public/fusio/index.htm`` i.e.:

  .. code-block:: javascript

      var fusioUrl = "http://localhost:8080/fusio/public/index.php/";

* **Apache module mod_rewrite is not activated**

  In case you use Apache as web server you must activate the module 
  ``mod_rewrite`` so that the ``public/.htaccess`` file is used. Besides 
  clean urls it contains an important rule which tells Apache to redirect the 
  ``Authorization`` header to Fusio otherwise Apache will remove the header and 
  Fusio can not authenticate the user

* **Fusio API returns an error**

  In this case Fusio can probably not write to the ``cache/`` folder. To fix the 
  problem you have to change the folder permissions so that the user of the web 
  server can write to the folder. If there is another error message it is maybe 
  a bug. Please report the issue to GitHub.

Marketplace
^^^^^^^^^^^

Fusio has a `marketplace`_ which contains a variety of apps for specific use
cases. Every app can be directly installed from the backend app under
System / Marketplace.

Updating
--------

There are two parts of Fusio which you can update. The backend system and the 
backend app. The backend app is the AngularJS application which connects
to the backend api and where you configure the system. The backend system 
contains the actual backend code providing the backend API and the API which you 
create with the system.

Server
^^^^^^

Fusio makes heavy use of composer. Because of that you can easily upgrade a 
Fusio system with the following composer command.

.. code-block:: text

    composer update fusio/impl

This has also the advantage that the version constraints of installed adapters
are checked and in case something is incompatible composer will throw an error.
It is also possible to simply replace the vendor folder with the folder from the
new release. In either case you have to run the following command after you have
updated the vendor folder:

.. code-block:: text

    php bin/fusio install

This gives Fusio the chance to adjust the database schema in case something has
changed with a new release.

Apps
^^^^

All apps can be updated at the Marketplace panel of the backend app. There you
can simply use the update button to receive the latest version of the app.


.. _download: http://www.fusio-project.org/download
.. _php-v8: https://github.com/pinepain/php-v8
.. _marketplace: https://www.fusio-project.org/marketplace

