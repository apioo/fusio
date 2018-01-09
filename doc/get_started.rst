
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

* **migration**

  Through migrations it is possible to execute i.e. sql queries on a connection. 
  This allows you to change your database schema on deployment.

  .. code-block:: yaml

      Default-Connection:
        - resources/migration/v1_schema.php

Through the command ``php bin/fusio deploy`` you can deploy the API. It is now 
possible to visit the API endpoint at: ``/todo``.

Access a non-public API endpoint
--------------------------------

The POST method of the todo API is not public, because of this you need an 
access token in order to send a POST request.

* **Assign the scope to your user**

  By default all routes are assigned to the ``todo`` scope. In order to use a 
  scope, the scope must be assigned to your user account. Therefor go to the 
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

  Note this generates an OAuth2 token with contains all scopes from your user 
  account. It is also possible to use the OAuth2 endpoint `/authorization/token`
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
