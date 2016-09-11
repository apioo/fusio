Fusio
=====

# About

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. It provides endpoint versioning, handling data from different data 
sources, schema definition (JsonSchema), automatic documentation generation and
secure authorization (OAuth2). More informations on 
http://www.fusio-project.org/

We think that there is a huge potential in the API economy. Whether you need an 
API to expose your business functionality or to develop One-Page web 
applications or Mobile-Apps. Because of this we think that Fusio is a great tool 
to simplify building such APIs.

# Overview

This section gives a high level overview what the Fusio system provides and how
the application is structured. Lets take a look at the components which are 
provided by Fusio:

![Overview](https://github.com/apioo/fusio/blob/master/doc/_static/overview.png)

## Fusio API

If you install a Fusio system it setups the default API with that it is possible
to manage the complete system. Because of that Fusio has some reserved paths 
which are needed by the system.

* `/backend`  
  Endpoints for configuring the system
* `/consumer`  
  Endpoints for the consumer i.e. register new accounts or create new apps 
* `/doc`  
  Endpoints for the documentation
* `/authorization`  
  Endpoints for the consumer to get i.e. information about the user itself and 
  to revoke an obtained access token
* `/export`  
  Endpoints to export the documentation into other formats i.e. swagger

All following apps are working with the API. Because of that it is also really 
easy to integrate Fusio into an existing system since you can call the endpoints 
from your application.

## Backend App

![Backend](https://github.com/apioo/fusio/blob/master/doc/_static/backend.png)

The backend app is the app where the administrator can configure the system. The 
app is located at `/backend.htm`.

## Developer App

![Developer](https://github.com/apioo/fusio/blob/master/doc/_static/developer.png)

The developer app is designed to quickly setup an API programm where new 
developers can register and create/manage their apps. The app is located at 
`/developer/`.

## Documentation App

![Documentation](https://github.com/apioo/fusio/blob/master/doc/_static/documentation.png)

The documentation app simply provides an overview of all available endpoints. 
It is possible to export the API definition into other schema formats like i.e. 
Swagger. The app is located at `/documentation/`.

# Installation

To install Fusio download the latest version and place the folder into the www 
directory of the webserver. After this Fusio can be installed in the following 
steps.

* __Adjust the configuration file__  
  Open the file `configuration.php` in the Fusio directory and change the key 
  `psx_url` to the domain pointing to the public folder. Also insert the 
  database credentials to the `psx_connection` keys.
* __Execute the installation command__  
  The installation script inserts the Fusio database schema into the provided 
  database. It can be executed with the following command 
  `php bin/fusio install`.
* __Create administrator user__  
  After the installation is complete you have to create a new administrator 
  account. Therefor you can use the following command `php bin/fusio adduser`. 
  Choose as account type "Administrator".

You can then login to the backend at `/backend.htm` where you can start to 
configure the system.

# Documentation

The offical documentation is available at http://fusio.readthedocs.org/

# Use cases

Today there are many use cases where you need a great documented REST API. In 
the following we list the most popular choices where Fusio comes in to play.

## Business functionality

Exposing an API of your business functionality is a great way to extend your 
product. You enable customers to integrate it into other applications which
gives the possibility to open up for new markets. With Fusio you can build such 
APIs and integrate them seamlessly into your product.

## Javascript applications

Javascript frameworks like i.e. AngularJS or EmberJS becoming more popular. With
Fusio you can easily build a backend for such applications. So you dont have to
build the backend part by yourself.

## Mobile apps

Almost all mobile apps need some form to interact with a remote service. This is
mostly done through REST APIs. With Fusio you can easily build such APIs which 
then can also be used by other applications.

# Reasons

* __Versionable endpoints__  
  With Fusio you can create flexible endpoints to design the API in the way you 
  like. Each endpoint can have multiple versions with different request and 
  response formats. This makes it easy to advance your API as it grows.
* __Action handling__  
  Fusio provides already many actions to handle common usecases. I.e. it is 
  possible to execute SQL queries against a database or send data into a message 
  queue. Also it is very easy to provide custom implementations.
* __Schema definition__  
  Fusio gives you the option to describe the data schema of the request and 
  response in the flexible JsonSchema format.
* __Automatic documentation__  
  Fusio generates automatically a documentation of the API endpoints based on 
  the provided schema definitions.
* __Secure authorization__  
  Fusio uses OAuth2 for API authorization. Each app can be limited to scopes to 
  request only specific endpoints of the API.

# Contribution

If you have found bugs or want to make feature requests use the bug tracker on 
GitHub. For code contributions feel free to send a pull request through GitHub, 
there we can discuss all details of the changes.
