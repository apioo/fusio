
Overview
========

About
-----

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. We think that there is a huge potential in the API economy. 
Whether you need an API to expose your business functionality, build micro 
services or to develop One-Page web applications or Mobile-Apps. Because of this 
we think that Fusio is a great tool to simplify building such APIs. More 
information on http://www.fusio-project.org/

Features
--------

Fusio covers all important aspects of the API lifecycle so you can concentrate
on building the actual business logic of your API.

* **Versioning**

  It is possible to define different versions of your endpoint. A concrete 
  version can be requested through the ``Accept`` header i.e. ``application/vnd.acme.v1+json``
* **Documentation**

  Fusio generates automatically a documentation of the API endpoints based on 
  the provided schema definitions.
* **Validation**

  Fusio uses the standard JSONSchema to validate incoming request data.
* **Authorization**

  Fusio uses OAuth2 for API authorization. Each app can be limited to scopes to 
  request only specific endpoints of the API.
* **Analytics**

  Fusio monitors all API activities and shows them on a dashboard so you always 
  know what is happening with your API. 
* **Rate limiting**

  It is possible to limit the requests to a specific threshold.
* **Specifications**

  Fusio generates different specification formats for the defined API endpoints
  i.e. OAI (Swagger), RAML.
* **User management**

  Fusio provides an API where new users can login or register a new account 
  through GitHub, Google, Facebook or through normal email registration.

Basically with Fusio you only have to define the schema (request/response) of 
your API endpoints and implement the business logic in a simple PHP file. All
other aspects are covered by Fusio.

Development
-----------

If you develop an API with Fusio you need to define a .fusio.yml deploy file 
which specifies the available routes and actions for the system. If a request 
schema is available for a method the input gets validated according to the 
schema. A deploy file looks like:

.. code-block:: yaml
    
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

This file can be deploy with the following command:

.. code-block:: text
    
    php bin/fusio deploy

The action of each route contains the file which handles the business logic. By 
default we use the PhpFile engine which uses a simple PHP file but you can also 
set another engine i.e. PhpClass or V8 to use either an actual php class or 
javascript code. More information in the src/ folder. In the following an 
example action to build an API response from a database:

.. code-block:: php
    
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

In the code we get the Default-Connection which we have defined previously in 
our ``.fusio.yml`` deploy file. In this case the connection returns a 
``\Doctrine\DBAL\Connection`` instance but we have already many adapters to 
connect to different services. Then we simply fire some queries and return the 
response.

Backend
-------

Fusio provides several apps which work with the internal backend API. These apps 
can be used to manage and work with the API. This section gives a high level 
overview what the Fusio system provides and how the application is structured. 
Lets take a look at the components which are provided by Fusio:

.. image:: _static/overview.png

API
^^^^

If you install a Fusio system it setups the default API with that it is possible
to manage the complete system. Because of that Fusio has some reserved paths 
which are needed by the system.

* ``/backend``

  Endpoints for configuring the system
* ``/consumer``

  Endpoints for the consumer i.e. register new accounts or create new apps 
* ``/doc``

  Endpoints for the documentation
* ``/authorization``

  Endpoints for the consumer to get i.e. information about the user itself and 
  to revoke an obtained access token
* ``/export``

  Endpoints to export the documentation into other formats i.e. swagger

Apps
----

All following apps are working with the API. Because of that it is also really 
easy to integrate Fusio into an existing system since you can call the endpoints 
from your application.

Backend
^^^^^^^

.. image:: _static/backend.png

The backend app is the app where the administrator can configure the system. The 
app is located at ``/fusio/``.

Developer
^^^^^^^^^

.. image:: _static/developer.png

The developer app is designed to quickly setup an API programm where new 
developers can register and create/manage their apps. The app is located at 
``/developer/``.

Documentation
^^^^^^^^^^^^^

.. image:: _static/documentation.png

The documentation app simply provides an overview of all available endpoints. 
It is possible to export the API definition into other schema formats like i.e. 
Swagger. The app is located at ``/documentation/``.
