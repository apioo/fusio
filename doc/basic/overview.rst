
Overview
========

About
-----

Fusio is as an open source api management and serverless platform which helps
to build great APIs while being self hosted and vendor independent. More
information on https://www.fusio-project.org/

API management and features
---------------------------

Fusio is an API management platform where you can configure routes which execute
specific actions. An action triggers your business logic, it is like a controller
in a classical framework, you can also think of it like a serverless lambda function,
which can be executed on a route call or via RPC. Fusio covers many aspects of the API
management life cycle so that you can concentrate on writing the actual business
logic of your API. The following feature list gives your a first overview:

Features
--------

* **OpenAPI generation**  

  Fusio generates automatically an OpenAPI specification for the defined routes.
* **SDK generation**  

  Fusio can automatically generate a client SDK for your API based on the defined schema.
* **Subscription support**  

  Fusio contains a subscription layer which helps to build pub/sub for your API.
* **Rate limiting**  

  Fusio provides a way to rate limit requests based on the user or app.
* **Authorization**  

  Fusio uses OAuth2 for API authorization.
* **RPC support**  

  Fusio provides RPC support, every action which you create can be also called via JsonRPC
* **Monetization**  

  Fusio provides a simple payment system to charge for specific routes.
* **Versioning**  

  It is possible to define different versions of your endpoint.
* **Validation**  

  Fusio uses the TypeSchema to automatically validate incoming request data
* **Analytics**  

  Fusio monitors all API activities and shows them on a dashboard.
* **User management**

  Fusio provides a developer app where new users can login or register a new account through GitHub, Google, Facebook or through normal email registration.
* **Logging**  

  All errors which occur in your endpoint are logged and are visible at the backend including all information from the request.
* **Connection**  

  Fusio provides an adapter system to connect to external services. By default we provide the HTTP and SQL connection type but there are many other types available i.e. MongoDB, Amqp, Cassandra.
* **Action**  

  Fusio contains an action ecosystem which helps to build APIs based on different sources, i.e. the SQL-Table actions provides an API based on a database table.
* **Migration**  

  Fusio has a migration system which allows you to change the database schema on deployment.
* **Testing**  

  Fusio provides an api test case wherewith you can test every endpoint response without setting up a local web server.

Architecture
------------

The basic building block of Fusio is the concept of an action. An action is
simply a PHP class which receives a request and returns a response. Around this
action Fusio handles all common logic like Authentication, Rate-Limiting, Schema
validation, Logging etc. The class has to implement the following signature:

.. code-block:: php
    
    <?php

    namespace App;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;

    class HelloWorld extends ActionAbstract
    {
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            return $this->response->build(200, [], [
                'hello' => 'world',
            ]);
        }
    }

To give you a first overview, every request which arrives at such an action goes
through the following lifecycle:

.. image:: ../_static/request_flow.png

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
a ``Doctrine\DBAL\Connection`` instance). A connection always returns a fully 
configured object so you never have to deal with any credentials in an action. 
There are already many different actions available which you can use i.e. to
create an API based on a database table.

With Fusio we want to remove as many layers as possible so that you can work
in your action directly with a specific library. Because of this Fusio has no 
model or entity system like many other frameworks, instead we recommend to write
plain SQL in case you work with a relational database. We think that building 
API endpoints based on models/entities limits the way how you would design a 
response. You only need to describe the request and response in the TypeSchema
format. This schema is then the contract of your API endpoint, how you produce 
this response technically is secondary. Fusio provides the mentioned 
connections, which help you to create complete customized responses based on 
complicated SQL queries, message queue inserts or multiple remote HTTP calls.

Development
-----------

Fusio provides two ways to develop an API. The first way is to build API 
endpoints only through the backend interface by using all available actions.
Through this you can solve already many tasks.

The other way is to use the deploy-mechanism. Through this you can use normal
PHP classes to implement your business logic and thus you can use the complete PHP 
ecosystem. Therefor you need to define a ``.fusio.yml`` `deploy file`_
which specifies the available routes and actions of the system. This file can be
deployed with the following command:

.. code-block:: text
    
    php bin/fusio deploy

The action of each route contains the source which handles the business logic. 
This can be i.e. a php class, a simple php file or a url. More information in
the ``src/`` folder. In the following an example action to build an API response 
from a database:

.. code-block:: php

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

In the code we get the ``System`` connection which returns a
``\Doctrine\DBAL\Connection`` instance but we have already `many adapters`_ to
connect to different services. Then we simply fire some queries and return the
response.

Backend
-------

Fusio provides several apps which work with the internal backend API. These apps 
can be used to manage and work with the API. This section gives a high level 
overview what the Fusio system provides and how the application is structured. 
Lets take a look at the components which are provided by Fusio:

.. image:: ../_static/overview.png

API
^^^^

If you install a Fusio system it setups the default API. Through the API it is 
possible to manage the complete system. Because of that Fusio has some reserved 
paths which are needed by the system.

* ``/backend``

  Endpoints for the system configuration
* ``/consumer``

  Endpoints for the consumer i.e. register new accounts or create new apps 
* ``/system``

  Endpoints for public system functions
* ``/authorization``

  Endpoints for the consumer to get i.e. information about the user itself and 
  to revoke an obtained access token

There is also a complete `documentation`_ about all internal API endpoints.

Apps
----

The following apps are working with the Fusio API.

Backend
^^^^^^^

.. image:: ../_static/backend.png

The backend app is the app where the administrator can configure the system. The 
app is located at ``/apps/fusio/``.

Marketplace
^^^^^^^^^^^

Fusio has a `marketplace`_ which contains a variety of apps for specific use
cases. Every app can be directly installed from the backend app under System /
Marketplace.

Use cases
---------

Today there are many use cases where you need a great documented REST API. In
the following we list the most popular choices where Fusio comes in to play.

Business functionality
^^^^^^^^^^^^^^^^^^^^^^

Exposing an API of your business functionality is a great way to extend your
product. You enable customers to integrate it into other applications which
gives the possibility to open up for new markets. With Fusio you can build such
APIs and integrate them seamlessly into your product. We also see many companies
which use the API itself as the core product.

Micro services
^^^^^^^^^^^^^^

With Fusio you can simply build small micro services which solve a specific task
in a complex system.

Javascript applications
^^^^^^^^^^^^^^^^^^^^^^^

Javascript frameworks like i.e. AngularJS or EmberJS becoming the standard. With
Fusio you can easily build a backend for such applications. So you dont have to
build the backend part by yourself.

Mobile apps
^^^^^^^^^^^

Almost all mobile apps need some form to interact with a remote service. This is
mostly done through REST APIs. With Fusio you can easily build such APIs which
then can also be used by other applications.

.. _adapter: http://www.fusio-project.org/adapter
.. _deploy file: http://fusio.readthedocs.io/en/latest/deploy.html
.. _swagger-ui: https://github.com/swagger-api/swagger-ui
.. _monaco editor: https://microsoft.github.io/monaco-editor/
.. _business logic: http://fusio.readthedocs.io/en/latest/development/business_logic.html
.. _many adapters: https://www.fusio-project.org/adapter
.. _documentation: https://demo.fusio-project.org/apps/internal/#!/page/about
.. _marketplace: https://www.fusio-project.org/marketplace
