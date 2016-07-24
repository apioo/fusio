
Installation
============

To install Fusio download the latest version and place the folder into the www 
directory of the webserver. After this Fusio can be installed in the following 
steps.

* **Adjust the configuration file**

  Open the file ``configuration.php`` in the Fusio directory and change the key 
  ``psx_url`` to the domain pointing to the public folder. Also insert the 
  database credentials to the ``psx_sql_*`` keys.
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
available at ``/backend.htm``.

Configure webserver
-------------------

It is recommended to setup a virtual host in your ``sites-available`` folder 
which points to the public folder of Fusio. After this you also have to change 
the configuration of the url i.e.:

.. code-block:: text

    'psx_url'      => 'http://api.acme.com',
    'psx_dispatch' => '',

Apache
^^^^^^

.. code-block:: text

    <VirtualHost *:80>
        ServerName api.acme.com
        DocumentRoot /var/www/fusio/public/
        ErrorLog /var/log/apache2/fusio-error.log
        CustomLog /var/log/apache2/fusio-access.log combined
    </VirtualHost>

Also you should enable the module ``mod_rewrite`` so that the .htaccess file in 
the public folder is used. The htaccess contains also an important rule which 
redirects the ``Authorization`` header to Fusio which is otherwise removed.

Adjust urls
-----------

There are three javascript apps which need to connect to the Fusio backend API.
The backend app, the consumer app and the documentation app. By default they try 
to guess the url of the API endpoint. If a app is not working properly the 
problem is probably that the javascript app can not correctly determine the API 
endpoint url. In this case you have to adjust the url in the following files:

* ``/public/backend.htm``
* ``/public/consumer/index.html``
* ``/public/documentation/index.html``

Backend
-------

At the endpoint ``/backend.htm`` you can login to the backend app. You should
be able to login with the username (which you have entered for the ``adduser``
command) and the password which you have used. 

Updating
--------

There are two parts of Fusio which you can update. The backend system and the 
backend app. The backend app is the AngularJS application which connects
to the backend api and where you configure the system. The backend system 
contains the actual backend code providing the backend API and the API which you 
build with the system.

Update backend system
^^^^^^^^^^^^^^^^^^^^^

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

Update backend app
^^^^^^^^^^^^^^^^^^

To update the backend app simply replace the following files from the new 
release:

 * ``public/backend.htm``
 * ``public/dist/fusio.min.js``
 * ``public/dist/fusio.min.css``
