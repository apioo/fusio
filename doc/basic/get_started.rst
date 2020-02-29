
Get started
===========

This chapter helps you to quickly create your first API with Fusio. In general
there are two ways how you can use Fusio:

* You can use Fusio to build the complete API through the backend UI. This means
  you use it more like a CMS to build your API.
* You can use Fusio as framework to build your API. This means you can develop
  your endpoint logic in simple PHP classes and define your route meta data in
  simple YAML files.

In this example we build our first API using Fusio as a framework.

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

      "/todo": !include resources/routes/todo/collection.yaml
      "/todo/:todo_id": !include resources/routes/todo/entity.yaml

* **schema**

  Contains the available request and response schema in the JSON-Schema format:

  .. code-block:: yaml

      Todo: !include resources/schema/todo/entity.json
      Todo-Collection: !include resources/schema/todo/collection.json
      Message: !include resources/schema/message.json

* **connection**

  Provides connections to a remote service i.e. mysql or mongodb. This 
  connection can be used inside an action:

  .. code-block:: yaml
    
      Default-Connection:
        class: Fusio\Adapter\Sql\Connection\SqlAdvanced
        config:
          url: "sqlite:///${dir.cache}/todo-app.db"

Through the command ``php bin/fusio deploy`` you can deploy the API. It is now 
possible to visit the API endpoint at: ``/todo``.

Migration
^^^^^^^^^

The demo endpoint works on an app_todo table. In order to create this table you
need to execute the migration files available at ``src/Migrations/System``. The
migrations can be executed with the following command:
``php bin/fusio migration:migrate --connection=System``

By default we use the System connection, which is the connection where Fusio is
installed but you can also use any other connection.

Access a non-public API endpoint
--------------------------------

The POST method of the todo API is not public, because of this you need an 
access token in order to send a POST request.

* **Assign the scope to your user**

  The routes are assigned to the ``todo`` scope. In order to use a scope, the
  scope must be assigned to your user account. Therefor go to the 
  user panel click on the edit button and assign the ``todo`` scope to your 
  user. It is also possible to set the default scopes for new users under 
  settings ``scopes_default``.

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

  Note this generates an OAuth2 token which contains all scopes from your user 
  account. It is also possible to use the OAuth2 endpoint ``/authorization/token``
  to create an access token with specific assigned scopes.

* **Request the non-public API endpoint**

  Now we can use the JWT as Bearer token in the ``Authorization`` header to 
  access the protected endpoint.

  .. code-block:: http

      POST /todo HTTP/1.1
      Host: 127.0.0.1
      Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5N2JkNDUzYjdlMDZlOWFlMDQxNi00YmY2MWFiYjg4MDJjZmRmOWZmN2UyNDg4OTNmNzYyYmU5Njc5MGUzYTk4NDQ3MDEtYjNkYTk1MDYyNCIsImlhdCI6MTQ5MTE2NzIzNiwiZXhwIjoxNDkxMTcwODM2LCJuYW1lIjoidGVzdCJ9.T49Af5wnPIFYbPer3rOn-KV5PcN0FLcBVykUMCIAuwI
      Content-Type: application/json
    
      {
        "title": "lorem ipsum",
        "content": "lorem ipsum"
      }
