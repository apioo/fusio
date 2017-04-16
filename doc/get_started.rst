
Get started
===========

Build an API endpoint
---------------------

Fusio provides a demo news API which is ready for deployment. Take a look at the 
``.fusio.yml`` file which contains the deployment configuration. The file 
contains several keys:

* **routes**

  Describes for each route the available request methods, whether the endpoint 
  is public or private, the available request/response schema and also the 
  action which should be executed:

  .. code-block:: yaml

    routes:
      /news:
        version: 1
        methods:
          GET:
            public: true
            response: News-Collection
            action: News-Collection

* **schema**

  Contains the available request and response schema in the JSON-Schema format:

  .. code-block:: yaml

    schema:
      News-Collection: !include resources/schema/news/collection.json
      News-Entity: !include resources/schema/news/entity.json

* **action**

  Contains the actual actions which are executed if an request arrives and which 
  produce the response:

  .. code-block:: yaml

    action:
      News-Collection:
        class: Fusio\Custom\Action\News\Collection
      News-Insert:
        class: Fusio\Custom\Action\News\Insert

* **connection**

  Provides connections to a remote service i.e. mysql or mongodb. This 
  connection can be used inside an action:

  .. code-block:: yaml

    connection:
      Acme-Mysql:
        class: Fusio\Adapter\Sql\Connection\Sql
        config:
          type: pdo_mysql
          host: localhost
          username: root
          password: 
          database: fusio

* **migration**

  Through migrations it is possible to execute i.e. sql queries on a connection. 
  This allows you to change your database schema on deployment.

  .. code-block:: yaml

    migration:
      Acme-Mysql:
        - resources/sql/v1_schema.sql

Through the command ``php bin/fusio deploy`` you can deploy the API. It is now 
possible to visit the API endpoint at: ``/news``.


Access a non-public API endpoint
--------------------------------

The POST method of the news API is not public, because of this you need an 
access token in order to send a POST request.

* **Create a scope**

  At first we must create a scope for the ``/news`` API endpoint. Therefor login 
  to the backend an go to the scope panel. Click on the plus button and create a 
  new scope ``news`` which has the ``/news`` route assigned.

* **Assign the scope to your user**

  In order to use a scope, the scope must be assigned to your user account. 
  Therefor go to the user panel click on the edit button and assign the ``news``
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

    POST /news HTTP/1.1
    Host: 127.0.0.1
    Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5N2JkNDUzYjdlMDZlOWFlMDQxNi00YmY2MWFiYjg4MDJjZmRmOWZmN2UyNDg4OTNmNzYyYmU5Njc5MGUzYTk4NDQ3MDEtYjNkYTk1MDYyNCIsImlhdCI6MTQ5MTE2NzIzNiwiZXhwIjoxNDkxMTcwODM2LCJuYW1lIjoidGVzdCJ9.T49Af5wnPIFYbPer3rOn-KV5PcN0FLcBVykUMCIAuwI
    Content-Type: application/json
    
    {
      "title": "lorem ipsum",
      "content": "lorem ipsum"
    }

