
<p align="center">
    <a href="https://www.fusio-project.org/" target="_blank"><img src="https://www.fusio-project.org/img/fusio_64px.png"></a>
</p>

# About

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. We think that there is a huge potential in the API economy. 
Whether you need an API to expose your business functionality, build micro 
services, develop SPAs or Mobile-Apps. Because of this we think that Fusio is a 
great tool to simplify building such APIs. More information on 
https://www.fusio-project.org/

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
* __Specification__  
  Fusio generates different specification formats for the defined API endpoints
  i.e. OAI (Swagger), RAML.
* __User management__  
  Fusio provides an API where new users can login or register a new account 
  through GitHub, Google, Facebook or through normal email registration.
* __Logging__  
  All errors which occur in your endpoint are logged and are visible at the 
  backend including all information from the request.
* __Connection__  
  Fusio provides an [adapter](https://www.fusio-project.org/adapter) system to
  connect to external services. By default we provide the HTTP and SQL 
  connection type but there are many other types available i.e. MongoDB, Amqp, 
  Cassandra.
* __Migration__  
  Fusio has a migration system which allows you to change the database schema
  on deployment.
* __Testing__  
  Fusio provides an api test case wherewith you can test every endpoint 
  response without setting up a local web server.

Basically with Fusio you only have to define the schema (request/response) of 
your API endpoints and implement the business logic. All other aspects are 
covered by Fusio.

# Architecture

To give you a first overview, every request which arrives at Fusio goes through
the following lifecycle:

![Request_Flow](https://github.com/apioo/fusio/blob/master/doc/_static/request_flow.png)

Fusio tries to assign the incoming request to a fitting route. The route 
contains all schema information about the incoming request and outgoing 
responses. Those schemas are also used at the documentation which is 
automatically available. If a request schema was provided the incoming request 
body gets validated after this schema. In case everything is ok the action 
which is assigned to the route gets executed.

An action represents the code which handles an incoming request and produces a 
response. Each action can use connections to accomplish this task. A connection 
uses a library which helps to work with a remote service. I.e. the SQL 
connection uses the Doctrine DBAL library to work with a database (it returns
a `Doctrine\DBAL\Connection` instance). A connection always returns a fully 
configured object so you never have to deal with any credentials in an action. 
Besides that there are already many different actions available which you can 
use i.e. to create an API based on a database table.

With Fusio we want to remove as many layers as possible so that you can work
in your action directly with a specific library. Because of this Fusio has no 
model or entity system like many other frameworks, instead we recommend to write
plain SQL in case you work with a relational database. We think that building API 
endpoints based on models/entities limits the way how you would design a 
response. You only need to describe the request and response in the JSON schema 
format. This schema is then the contract of your API endpoint, how you produce 
this response technically is secondary. Fusio provides the mentioned 
connections, which help you to create complete customized responses based on 
complicated SQL queries, message queue inserts or multiple remote HTTP calls.

# Development

Fusio provides two ways to develop an API. The first way is to build API 
endpoints only through the backend interface by using all available actions.
Through this you can solve already many tasks especially through the usage of
the [v8 action](https://www.fusio-project.org/documentation/v8).

The other way is to use the deploy mechanism. Through this you can use normal
PHP files to implement your business logic and thus you can use the complete PHP 
ecosystem. Therefor you need to define a `.fusio.yml` 
[deploy file](http://fusio.readthedocs.io/en/latest/deploy.html) which specifies 
the available routes and actions of the system. This file can be deployed with 
the following command:

```
php bin/fusio deploy
```

The action of each route contains the source which handles the business logic. 
This can be i.e. a simple php file, php class or a url. More information in the 
`src/` folder. In the following an example action to build an API response 
from a database:

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
[many adapters](https://www.fusio-project.org/adapter) to connect to different 
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

There is also a complete [documentation](http://demo.fusio-project.org/internal/#!/page/about)
about all internal API endpoints.

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

## Swagger-UI

![Swagger-UI](https://github.com/apioo/fusio/blob/master/doc/_static/swagger-ui.png)

The [swagger-ui](https://github.com/swagger-api/swagger-ui) app renders a 
documentation based on the OpenAPI specification. The app is located at 
`/swagger-ui/`.

# Installation

It is possible to install Fusio either through composer or manually file 
download. Place the project into the www directory of the web server.

## Composer

```
composer create-project fusio/fusio
```

## Download

https://github.com/apioo/fusio/releases

## Configuration

* __Adjust the configuration file__  
  Open the file `.env` in the Fusio directory and change the key 
  `FUSIO_URL` to the domain pointing to the public folder. Also insert the 
  database credentials to the `FUSIO_DB_*` keys.
* __Execute the installation command__  
  The installation script inserts the Fusio database schema into the provided 
  database. It can be executed with the following command 
  `php bin/fusio install`.
* __Create administrator user__  
  After the installation is complete you have to create a new administrator 
  account. Therefor you can use the following command `php bin/fusio adduser`. 
  Choose as account type "Administrator".

You can verify the installation by visiting the `FUSIO_URL` with a browser. You
should see a API response that the installation was successful. The backend is
available at `/fusio/`.

## Docker

Alternatively it is also possible to setup a Fusio system through docker. This
has the advantage that you automatically get a complete running Fusio system
without configuration. This is especially great for testing and evaluation. To 
setup the container you have to checkout the [repository](https://github.com/apioo/fusio-docker) 
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
