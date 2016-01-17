
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
  Choose as account type "Administrator" and save the generated password.

You can verify the installation by visiting the ``psx_url`` with a browser. You
should see a API response that the installation was successful.

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


Backend
-------

At the endpoint ``/backend.htm`` you can login to the backend app. You should
be able to login with the username (which you have entered for the ``adduser``
command) and the password which was generated. 

