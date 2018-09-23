
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

# Why

The originally idea of Fusio was to provide a tool which lets you easily build a
great API beside an existing application. I.e. in case you have already a web
application on a domain `acme.com` Fusio helps you to build the fitting API
at `api.acme.com`. Beside this use case you can also use Fusio to build a new 
API from scratch or use it internally i.e. for micro services.

To build the API Fusio can connect to many different databases, message queue
systems or internal web services. There are also many ways to integrate your
[business logic](http://fusio.readthedocs.io/en/latest/development/business_logic.html)
into the API of Fusio.

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
  i.e. OpenAPI, Swagger, RAML.
* __Subscription__  
  Fusio contains a subscription layer which helps to build pub/sub for your API.
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
There are already many different actions available which you can use i.e. to
create an API based on a database table.

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
the [PHP-Sandbox](https://www.fusio-project.org/documentation/php) or
[V8-Processor](https://www.fusio-project.org/documentation/v8) action.

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
This can be i.e. a php class, a simple php file or a url. More information in
the [development doc](DEVELOPMENT.md). In the following an example action to
build an API response from a database:

```php
<?php

namespace App\Todo;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class Collection extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');

        $count   = $connection->fetchColumn('SELECT COUNT(*) FROM app_todo');
        $entries = $connection->fetchAll('SELECT * FROM app_todo WHERE status = 1 ORDER BY insertDate DESC LIMIT 16');

        return $this->response->build(200, [], [
            'totalResults' => $count,
            'entry' => $entries,
        ]);
    }
}

```

In the code we get the `System` connection which returns a
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

You can either manually install Fusio with the steps below or you can also use
the browser based installer at `public/install.php`. Note because of security
reasons it is highly recommended to remove the installer script after the
installation.

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

Here we list all available documentation resources. If these resources dont
answer your questions or you want to provide feedback feel free to create an
issue on GitHub.

* [Getting started](http://fusio-project.org/bootstrap)  
* [Manual](https://fusio.readthedocs.io/en/latest/) 
* [Recipes](http://fusio-project.org/documentation/recipes) 
* [Videos](http://fusio-project.org/documentation/videos)
* [Backend API](http://demo.fusio-project.org/internal/#!/page/about)
* [PHP API](https://www.fusio-project.org/documentation/php)

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

Contributions to the project are always appreciated. There are many options
available to improve the project (which is not limited to coding). The following
list shows some ways how you can participate:

## Developing

If you are a PHP or Javascript developer you can help to improve the system. 
If you want to create a new feature it is in general recommended to create a new
issue where we can talk about the feature before you start to hack on it. So
there are three main components of Fusio:

**[Backend-API](https://github.com/apioo/fusio-impl)**

The backend API is the core of the system developed in PHP, which provides the
basic functionality of Fusio. This is the place to develop new core features and 
improvements.

**[Adapter](https://www.fusio-project.org/adapter)**

An adapter is a plugin to the Fusio system which can be used to connect to other
remote services. I.e. you could create a new adapter which speaks to a specific
API or other remote service. This is easy to develop since you can build it in
a separate repository. Please use the keyword `fusio-adapter` in your
`composer.json` file so that adapter gets listed automatically on our website.

**[Backend-App](https://github.com/apioo/fusio-backend)**

This is the AngularJS app which is used as GUI to control the backend. It is
the main app to improve the Fusio backend. But you are also free to develop new
apps for special use cases which talk to the internal API of Fusio.

## Testing

In general we have a high PHPUnit test case coverage and also automatic 
end-to-end AngularJS tests using protractor and selenium. Beside this it is
always great if users checkout the current master version of the project and try
to test every aspect of the system. In case you have found an issue please
report it through the issue tracker.

## Documentation

We want to create a sytem which is easy to use also by novice users. To enable
everybody to start using Fusio we need a simple to understand documentation.
Since we have not always the view of a novice developer please let us know about
chapters which are difficult to understand or topics which are missing. You can
also send us directly a pull request with an improved version. The main
documentation of Fusio is available at [readthedocs](http://fusio.readthedocs.io/en/latest/).
The documentation source is available in the `docs/` folder.

# Support

## Promotion

If you are a blogger or magazine we would be happy if you like to cover Fusio.
Please take a look at the Media section of our [About Page](https://www.fusio-project.org/about)
to download the official icon set. In case you have any questions please write
us a message directly so we can help you to create great content.

## Consulting

If you are a company or freelancer and want to get detailed information how you
can use Fusio you can contact us for consulting. In the workshop we try to find
the best way how you can use/integrate Fusio also we try to explain the
functionality and answer your questions.

## Donations

If this project helps you to generate revenue or in general if you like to
support the project you can donate any amount through paypal. We like to thank
every user who has donated to the project.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/fusioapi)
