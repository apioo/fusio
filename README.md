Fusio
=====

# About

Fusio is a web application written in PHP to create RESTful APIs. We think that 
there is a huge potential in the API economy. Whether you need an API to expose 
your business functionality or to develop One-Page web applications or 
Mobile-Apps. Because of this we think that Fusio is a great tool to simplify 
building such APIs.

# Installation

To install Fusio download the latest version and place the folder into the www 
directory of the webserver. After this Fusio can be installed in three simple 
steps.

 * Adjust the configuration file
   Open the file configuration.php in the Fusio directory and change the key 
   `psx_url` to the domain pointing to the public folder. Also insert the database 
   credentials to the `psx_sql_*` keys.
 * Execute the installation command
   The installation script inserts the Fusio database schema into the provided 
   database. It can be executed with the following command `php bin/fusio install`.
 * Create administrator user
   After the installation is complete you have to create a new administrator 
   account. Therefor you can use the following command `php bin/fusio adduser`. 
   After this it is possible to login to the backend at `/backend.htm`.

# Contribution

For PHP code please follow the PSR-2 coding-style and add a test case for the 
changes. If you want to develop a new complex feature please get in contact with 
us beforehand to discuss whether it is needed or someone is already working on 
such a topic. Then send a pull request through GitHub to the appropriated 
branch. There we can discuss all details of the changes.

