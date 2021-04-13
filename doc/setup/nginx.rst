
Nginx
=====
This instruction based on a on standard Nginx installation on Ubuntu 20.04.1 LTS and has been double checked on Debian 10.


Php installation
----------------

Standard setup of nginx doesn't contain php installation. Here is a sample how to install php in a dedicated version (e.g. 7.4) with required modules (tested on Ubuntu 20.04.1):

.. code-block:: text

  apt update
  apt -y install software-properties-common
  add-apt-repository -y ppa:ondrej/php

  systemctl disable --now apache2
  apt -y install php7.4-fpm
  apt -y install php7.4-mysql
  apt -y install php7.4-mbstring
  apt -y install php7.4-soap
  apt -y install php7.4-xml
  apt -y install php7.4-curl
  apt -y install php7.4-zip

  apt -y upgrade

On Debian 10 the current version (8.0) of php has been installed with at least required modules like this:

.. code-block:: text

  systemctl disable --now apache2

  apt -y install software-properties-common
  wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add -
  echo "deb https://packages.sury.org/php/ buster main" | tee /etc/apt/sources.list.d/php.list
  apt update

  apt -y install php php-fpm php-mysql php-mbstring php-soap php-xml php-curl php-zip
  apt -y upgrade


Fusio site creation
-------------------
It is recommended to create a own server block (site) for Fusio. Create a new folder path ``/var/www/fusio/html``, download the desired version of Fusio from `https://github.com/apioo/fusio/releases <https://github.com/apioo/fusio/releases>`_ and unzip it to the new created path.

**Caution**: To ensure that Fusio can be installed via php script in browser, the Nginx user (default is ``www-data``) temporary should have ownership on root folder and new server block. This has to be undo after finalization!

.. code-block:: text

  chown www-data:www-data /var/www
  chown -R www-data:www-data /var/www/fusio
  chmod 755 /var/www
  chmod -R 755 /var/www/fusio

Site configuration
------------------
To configure the new site is recommended to copy the default configuration:

.. code-block:: text

  cp /etc/nginx/sites-available/default /etc/nginx/sites-available/fusio

Then this new configuration ``/etc/nginx/sites-available/fusio`` must be adapted to **your server name and php version**. Sample:

.. code-block:: text

  listen 80;
  listen [::]:80;
  root /var/www/fusio/html/public;
  index index.html index.htm index.nginx-debian.html index.php;
  server_name [your.domain];

  location / {
    try_files $uri $uri/ /index.php$is_args$args;
  }
  location ~ \.php$ {
    include snippets/fastcgi-php.conf;
  	fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
      
    fastcgi_split_path_info ^(.+\.php)(/.*)$;
  	include fastcgi_params;
  	fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
  	fastcgi_param DOCUMENT_ROOT $realpath_root;
  }
  
  error_log /var/log/nginx/fusio_error.log;
  access_log /var/log/nginx/fusio_access.log;

The follwing entry must be enabled either in the standard nginx configuration file ``/etc/nginx/nginx.conf`` (for all your sites) or in the configuration file of the new Fusio site ``/etc/nginx/sites-available/fusio``:

.. code-block:: text

  server_names_hash_bucket_size 64;

Finally the new available site must be enabled by creating a link:

.. code-block:: text

  ln -s /etc/nginx/sites-available/fusio /etc/nginx/sites-enabled/

**Optionally**: If you want to install a certificate to reach Fusio by https this is the right point of time to do it.

The Nginx configuration now can be tested and if successfully restarted:

.. code-block:: text

  nginx -t
  systemctl restart nginx

Fusio setup
-----------

Now the installation script ``http://[your.doman]/install.php`` can be used in a browser to setup Fusio.

Cleanup
-------
As mentioned above after installation the ownership should be corrected to limit extended permissions only to the ``public/apps`` and ``cache`` folder. For security reasons remove the installation script. Sample for default Nginx user:

.. code-block:: text

  systemctl stop nginx
  
  rm /var/www/fusio/html/public/install.php
  chown root:root /var/www
  chown -R root:root /var/www/fusio
  chown -R www-data:www-data /var/www/fusio/html/public/apps
  chown -R www-data:www-data /var/www/fusio/html/cache
  
  systemctl start nginx

Login to backend
----------------
Now you should be able to login to the backend at ``http://[your.doman]/apps/fusio`` with the user you have created at Fusio setup.

