
Shared-Hosting
==============

If you want to run Fusio on a shared-hosting environment it is possible but in 
general not recommended since you can not properly configure the web server and
access the CLI. Therefore you can not use the deploy command which simplifies
development. The biggest problem of a shared hosting environment is that you can 
not set the document root to the ``public/`` folder. If you place the following 
``.htaccess`` file in the directory you can bypass this problem by redirecting 
all requests to the ``public/`` folder.

.. code-block:: text

    RewriteEngine On
    RewriteBase /fusio/
    
    RewriteCond %{THE_REQUEST} /public/([^\s?]*) [NC]
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ public/index.php/$1 [L,QSA]

While this may work many shared hosting provider have strict limitations of 
specific PHP functions which are maybe used by Fusio and which produce other
errors.

