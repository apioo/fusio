
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

Docker
------

Alternatively it is also possible to setup a Fusio system through docker. This
has the advantage that you automatically get a complete running Fusio system
without configuration. This is especially great for testing and evaluation. To 
setup the container you have to checkout the `repository`_ and run the following 
command:

.. code-block:: text

    docker-compose up -d

This builds the Fusio system with a predefined backend account. The credentials 
are taken from the env variables ``FUSIO_BACKEND_USER``, ``FUSIO_BACKEND_EMAIL`` 
and ``FUSIO_BACKEND_PW`` in the `docker-compose.yml`_. If you are planing to run 
the container on the internet you MUST change these credentials.

Web server
----------

It is recommended to setup a virtual host in your ``sites-available`` folder 
which points to the public folder of Fusio. After this you also have to change 
the configuration of the url i.e.:

.. code-block:: text

    'psx_url' => 'http://api.acme.com',

Apache
^^^^^^

.. code-block:: text

    <VirtualHost *:80>
        ServerName api.acme.com
        DocumentRoot /var/www/html/fusio/public
    
        <Directory /var/www/html/fusio/public>
            Options FollowSymLinks
            AllowOverride All
            Require all granted
    
            # rewrite
            RewriteEngine On
            RewriteBase /
    
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule (.*) /index.php/$1 [L]
    
            RewriteCond %{HTTP:Authorization} ^(.*)
            RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
        </Directory>
    
        # log
        LogLevel warn
        ErrorLog ${APACHE_LOG_DIR}/fusio.error.log
        CustomLog ${APACHE_LOG_DIR}/fusio.access.log combined
    </VirtualHost>

You should enable the module ``mod_rewrite`` so that the .htaccess file in the 
public folder is used. It is also possible to include the htaccess commands 
directly into the virtual host which also increases performance. The htaccess 
contains an important rule which redirects the ``Authorization`` header to Fusio 
which is otherwise removed. If the .htaccess file does not work please check 
whether the ``AllowOverride`` directive is set correctly i.e. to ``All``.

Shared-Hosting
^^^^^^^^^^^^^^

If you want to run Fusio on a shared-hosting environment it is possible but in 
general not recommended since you can not properly configure the web server and
access the CLI. Therefore you can not use the deploy command which simplifies
development. The bigest problem of a shared hosting environment is that you can 
not set the document root to the ``public/`` folder. If you place the following 
``.htaccess`` file in the directory you can bypass this problem by redirecting 
all requests to the ``public/`` folder.

.. code-block:: text

    RewriteEngine on
    RewriteRule (.*) public/$1/

While this may work many shared hosting provider have strict limitations of 
specific PHP functions which are maybe used by Fusio and which produce other
errors.

Javascript V8
-------------

Fusio provides an adapter which lets you write the endpoint logic in simple 
javascript. To use this adapter you need to install the ``php-v8`` extension.
Installation instructions are available at the `php-v8`_ repository

Apps
----

There are three javascript apps which can connect to the Fusio backend API. The 
backend, developer and documentation app. By default they try to guess the url 
of the API endpoint. If an app is not working properly the problem is probably 
that the javascript app can not correctly determine the API endpoint url. In 
this case you have to adjust the url in the following files:

* ``/public/fusio/index.htm``
* ``/public/developer/index.html``
* ``/public/documentation/index.html``

These apps are of course optional. If you dont want to use them you could also
simply delete the folder.

Backend
^^^^^^^

At the endpoint ``fusio/`` you can login to the backend app. You should
be able to login with the username (which you have entered for the ``adduser``
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

App
^^^

To update the backend app simply replace the javascript and css files from the 
new release:

 * ``public/fusio/``


.. _download: http://www.fusio-project.org/download
.. _repository: https://github.com/apioo/fusio-docker
.. _docker-compose.yml: https://github.com/apioo/fusio-docker/blob/master/docker-compose.yml
.. _php-v8: https://github.com/pinepain/php-v8

