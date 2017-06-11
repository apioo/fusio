
<p align="center">
    <a href="http://www.fusio-project.org/" target="_blank"><img src="http://www.fusio-project.org/img/fusio_64px.png"></a>
</p>

# About

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. We think that there is a huge potential in the API economy. 
Whether you need an API to expose your business functionality, build micro 
services, develop SPAs or Mobile-Apps. Because of this we think that Fusio is a 
great tool to simplify building such APIs. More information on 
http://www.fusio-project.org/

# Features

Fusio covers all important aspects of the API lifecycle so you can concentrate
on building the actual business logic of your API.

* __Versioning__  
  It is possible to define different versions of your endpoint. A concrete 
  version can be requested through the `Accept` header i.e. `application/vnd.acme.v1+json`
* __Documentation__  
  Fusio generates automatically a documentation of the API endpoints based on 
  the provided schema definitions.
* __Validation__  
  Fusio uses the standard JSONSchema to validate incoming request data
* __Authorization__  
  Fusio uses OAuth2 for API authorization. Each app can be limited to scopes to 
  request only specific endpoints of the API.
* __Analytics__  
  Fusio monitors all API activities and shows them on a dashboard so you always 
  know what is happening with your API. 
* __Rate limiting__  
  It is possible to limit the requests to a specific threshold.
* __Specifications__  
  Fusio generates different specification formats for the defined API endpoints
  i.e. OAI (Swagger), RAML
* __User management__  
  Fusio provides an API where new users can login or register a new account 
  through GitHub, Google, Facebook or through normal email registration

# Development

If you develop an API with Fusio you need to define a `.fusio.yml` deploy file
which specifies the available routes and actions for the system. If a request
schema is available for a method the input gets validated according to the 
schema. A deploy file looks like:

```yaml
routes:
  "/todo":
    version: 1
    methods:
      GET:
        public: true
        response: Todo-Collection
        action: "${dir.src}/Todo/collection.php"
      POST:
        public: false
        request: Todo
        response: Todo-Message
        action: "${dir.src}/Todo/insert.php"
  "/todo/:todo_id":
    version: 1
    methods:
      GET:
        public: true
        response: Todo
        action: "${dir.src}/Todo/row.php"
      DELETE:
        public: false
        response: Todo-Message
        action: "${dir.src}/Todo/delete.php"
schema:
  Todo: !include resources/schema/todo/entity.json
  Todo-Collection: !include resources/schema/todo/collection.json
  Todo-Message: !include resources/schema/todo/message.json
connection:
  Default-Connection:
    class: Fusio\Adapter\Sql\Connection\SqlAdvanced
    config:
      url: "sqlite:///${dir.cache}/todo-app.db"
migration:
  Default-Connection:
    - resources/sql/v1_schema.sql
```

This file can be deploy with the following command:

```
php bin/fusio deploy
```

The action of each route contains the file which handles the business logic. By
default we use the `PhpFile` engine which uses a simple PHP file but you
can also set another engine i.e. `PhpClass` or `V8` to use either an actual php 
class or javascript code. More information in the `src/` folder. In the 
following an example action to build an API response from a database:

```php
<?php
/**
 * @var \Fusio\Engine\ConnectorInterface $connector
 * @var \Fusio\Engine\RequestInterface $request
 * @var \Fusio\Engine\Response\FactoryInterface $response
 * @var \Fusio\Engine\ProcessorInterface $processor
 * @var \Psr\Log\LoggerInterface $logger
 * @var \Psr\SimpleCache\CacheInterface $cache
 */

/** @var \Doctrine\DBAL\Connection $connection */
$connection = $connector->getConnection('Default-Connection');

$count   = $connection->fetchColumn('SELECT COUNT(*) FROM app_todo');
$entries = $connection->fetchAll('SELECT * FROM app_todo WHERE status = 1 ORDER BY insertDate DESC LIMIT 16');

return $response->build(200, [], [
    'totalResults' => $count,
    'entry' => $entries,
]);

```

In the code we get the `Default-Connection` which we have defined previously in
our `.fusio.yml` deploy file. In this case the connection returns a 
`\Doctrine\DBAL\Connection` instance but we have already 
[many adapters](http://www.fusio-project.org/adapter) to connect to different 
services. Then we simply fire some queries and return the response.

# Backend

Fusio provides several apps which work with the internal backend API. These apps
can be used to manage and work with the API. This section gives a high level 
overview what the Fusio system provides and how the application is structured. 
Lets take a look at the components which are provided by Fusio:

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

The following apps are working with the Fusio API.

## Backend

![Backend](https://github.com/apioo/fusio/blob/master/doc/_static/backend.png)

The backend app is the app where the administrator can configure the system. The
app is located at `/fusio/`.

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

It is possible to install Fusio either through composer or install it manually.
Place the project into the www directory of the web server.

## Composer

```
composer create-project fusio/fusio
```

## Download

https://github.com/apioo/fusio/releases

## Configuration

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

You can login to the backend at `/fusio`.

## Deploy

To deploy the sample API you can run the following command:

```
php bin/fusio deploy
```

## Docker

Alternatively it is also possible to setup a Fusio system through docker. This
has the advantage that you automatically get a complete running Fusio system
without configuration. This is especially great for testing and evaluation. To 
setup the container you have to checkout the [repository](https://github.com/apioo/fusio/releases) 
and run the following command:

```
docker-compose up -d
```

This builds the Fusio system with a predefined backend account. The credentials 
are taken from the env variables `FUSIO_BACKEND_USER`, `FUSIO_BACKEND_EMAIL` 
and `FUSIO_BACKEND_PW` in the `docker-compose.yml`. If you are planing to run 
the container on the internet you must change these credentials.

# Documentation

The official documentation is available at http://fusio.readthedocs.org/

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
