
Apache
======

It is recommended to setup a virtual host in your ``sites-available`` folder 
which points to the public folder of Fusio. After this you also have to change 
the configuration of the url i.e.:

.. code-block:: text

    'psx_url'      => 'http://api.acme.com',
    'psx_dispatch' => '', // since we use clean urls

The following contains a sample apache config:

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

On a Ubuntu/Debian system you could place the config at
``/etc/apache/sites-available/fusio.conf``. Through the command ``a2ensite fusio``
you would activate the site.

You should enable the module ``mod_rewrite`` so that the .htaccess file in the 
public folder is used. It is also possible to include the htaccess commands 
directly into the virtual host which also increases performance. The htaccess 
contains an important rule which redirects the ``Authorization`` header to Fusio 
which is otherwise removed. If the .htaccess file does not work please check 
whether the ``AllowOverride`` directive is set correctly i.e. to ``All``.
