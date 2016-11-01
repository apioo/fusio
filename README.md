Fusio
=====

# About

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. We think that there is a huge potential in the API economy. 
Whether you need an API to expose your business functionality, build micro 
services or to develop One-Page web applications or Mobile-Apps. Because of this 
we think that Fusio is a great tool to simplify building such APIs.

# Features

Fusio covers all important aspects of the API lifecycle so you can concentrate
on building the actual business logic for your API.

* __Versioning__  
  It is possible to define different versions of your endpoint. A concrete 
  version can be requested through the `Accept` header i.e. `application/vnd.acme.v1+json`
* __Documentation__  
  Fusio generates automatically a documentation of the API endpoints based on 
  the provided schema definitions.
* __Authorization__  
  Fusio uses OAuth2 for API authorization. Each app can be limited to scopes to 
  request only specific endpoints of the API.
* __Analytics__  
  Fusio monitors all API activities and shows them on a dashboard so you always 
  know what is happening with your API. 
* __Rate limiting__  
  It is possible to limit the requests to a specific threshold.

Fusio provides already many actions to handle common use cases. I.e. it is 
possible to execute SQL queries against a database or send data into a message 
queue. It is also very easy to build a customized action. Fusio provides also an 
[adapter system](http://www.fusio-project.org/adapter) through this it is 
possible to share those actions via composer.

# System

This section gives a high level overview what the Fusio system provides and how
the application is structured. Lets take a look at the components which are 
provided by Fusio:

![Overview](https://github.com/apioo/fusio/blob/master/doc/_static/overview.png)

## API

If you install a Fusio system it setups the default API. Through the API it is 
possible to manage the complete system. Because of that Fusio has some reserved 
paths which are needed by the system.

* `/backend`  
  Endpoints for the system configuration
* `/consumer`  
  Endpoints for the consumer i.e. register new accounts or create new apps 
* `/doc`  
  Endpoints for the documentation
* `/authorization`  
  Endpoints for the consumer to get i.e. information about the user itself and 
  to revoke an obtained access token
* `/export`  
  Endpoints to export the documentation into other formats i.e. swagger

# Apps

The following apps are working with the Fusio API. Because of that it is also 
really easy to integrate Fusio into an existing system since you can call those 
endpoints also from your application.

## Backend

![Backend](https://github.com/apioo/fusio/blob/master/doc/_static/backend.png)

The backend app is the app where the administrator can configure the system. The 
app is located at `/backend.htm`.

## Developer

![Developer](https://github.com/apioo/fusio/blob/master/doc/_static/developer.png)

The developer app is designed to quickly setup an API program where new 
developers can register and create/manage their apps. The app is located at 
`/developer/`.

## Documentation

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
APIs and integrate them seamlessly into your product. We also see many companies
which use the API itself as the core product.

## Micro services

With Fusio you can simply build small micro services which solve a specific task
in a complex system.

## Javascript applications

Javascript frameworks like i.e. AngularJS or EmberJS becoming the standard. With
Fusio you can easily build a backend for such applications. So you dont have to
build the backend part by yourself.

## Mobile apps

Almost all mobile apps need some form to interact with a remote service. This is
mostly done through REST APIs. With Fusio you can easily build such APIs which 
then can also be used by other applications.

# Contribution

If you have found bugs or want to make feature requests use the bug tracker on 
GitHub. For code contributions feel free to send a pull request through GitHub, 
there we can discuss all details of the changes.
