
Get started
===========

Build an API endpoint
---------------------

Fusio provides a demo todo API which is ready for deployment. Take a look at the 
``.fusio.yml`` file which contains the deployment configuration. The file 
contains several keys:

* **routes**

  Describes for each route the available request methods, whether the endpoint 
  is public or private, the available request/response schema and also the 
  action which should be executed:

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

* **schema**

  Contains the available request and response schema in the JSON-Schema format:

  .. code-block:: yaml

    schema:
      Todo: !include resources/schema/todo/entity.json
      Todo-Collection: !include resources/schema/todo/collection.json
      Todo-Message: !include resources/schema/todo/message.json

* **connection**

  Provides connections to a remote service i.e. mysql or mongodb. This 
  connection can be used inside an action:

  .. code-block:: yaml
    
    connection:
      Default-Connection:
        class: Fusio\Adapter\Sql\Connection\SqlAdvanced
        config:
          url: "sqlite:///${dir.cache}/todo-app.db"

* **migration**

  Through migrations it is possible to execute i.e. sql queries on a connection. 
  This allows you to change your database schema on deployment.

  .. code-block:: yaml

    migration:
      Default-Connection:
        - resources/sql/v1_schema.php

Through the command ``php bin/fusio deploy`` you can deploy the API. It is now 
possible to visit the API endpoint at: ``/todo``.

Access a non-public API endpoint
--------------------------------

The POST method of the todo API is not public, because of this you need an 
access token in order to send a POST request.

* **Create a scope**

  At first we must create a scope for the ``/todo`` API endpoint. Therefor login 
  to the backend an go to the scope panel. Click on the plus button and create a 
  new scope ``todo`` which has the ``/todo`` route assigned.

* **Assign the scope to your user**

  In order to use a scope, the scope must be assigned to your user account. 
  Therefor go to the user panel click on the edit button and assign the ``todo``
  scope to your user.

* **Request a JWT**

  Now you can obtain a JWT through a simple HTTP request to the 
  ``consumer/login`` endpoint.

  .. code-block:: http

    POST /consumer/login HTTP/1.1
    Host: 127.0.0.1
    Content-Type: application/json
    
    {
      "username": "[username]",
      "password": "[password]"
    }

  Which returns a token i.e.:

  .. code-block:: json

    {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5N2JkNDUzYjdlMDZlOWFlMDQxNi00YmY2MWFiYjg4MDJjZmRmOWZmN2UyNDg4OTNmNzYyYmU5Njc5MGUzYTk4NDQ3MDEtYjNkYTk1MDYyNCIsImlhdCI6MTQ5MTE2NzIzNiwiZXhwIjoxNDkxMTcwODM2LCJuYW1lIjoidGVzdCJ9.T49Af5wnPIFYbPer3rOn-KV5PcN0FLcBVykUMCIAuwI"
    }

* **Request the non-public API endpoint**

  Now we can use the JWT as Bearer token in the Authorization header.

  .. code-block:: http

    POST /todo HTTP/1.1
    Host: 127.0.0.1
    Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5N2JkNDUzYjdlMDZlOWFlMDQxNi00YmY2MWFiYjg4MDJjZmRmOWZmN2UyNDg4OTNmNzYyYmU5Njc5MGUzYTk4NDQ3MDEtYjNkYTk1MDYyNCIsImlhdCI6MTQ5MTE2NzIzNiwiZXhwIjoxNDkxMTcwODM2LCJuYW1lIjoidGVzdCJ9.T49Af5wnPIFYbPer3rOn-KV5PcN0FLcBVykUMCIAuwI
    Content-Type: application/json
    
    {
      "title": "lorem ipsum",
      "content": "lorem ipsum"
    }

