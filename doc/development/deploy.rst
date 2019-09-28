
Deploy
======

The ``.fusio.yml`` deploy file is the main configuration file to develop an API 
with Fusio. This chapter explains in detail the format.

routes
------

A route is the rule which redirects the incoming request to an action. If a 
request arrives the first route which matches is used. In order to be able to
evolve an API it is possible to add multiple versions for the same route. For 
each version it is possible to specify the allowed request methods. Each method
describes the request and response schema and the action which is executed upon 
request. If a request method is public it is possible to request the API 
endpoint without an access token.

.. code-block:: yaml
    
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

The ``request`` and ``response`` key reference a schema name which was defined
under the ``schema`` key. It is also possible to use the ``Passthru`` schema
which simply redirects all data. The ``action`` key reference an action.

Path
^^^^

The path can contain variable path fragments. It is possible to access these 
variable path fragments inside an action. The following list describes the 
syntax.

* ``/news``
  No variable path fragment only the request to ``/news`` matches this route

* ``/news/:news_id``
  Simple variable path fragment. This route matches to any value except a slash.
  I.e. ``/news/foo`` or ``/news/12`` matches this route

* ``/news/$year<[0-9]+>``
  Variable path fragment with a regular expression. I.e. only ``/news/2015`` 
  matches this route

* ``/file/*path``
  Variable path fragment which matches all values. I.e. ``/file/foo/bar`` or 
  ``/file/12`` matches this route

Status
^^^^^^

Beside the ``version`` every route can also have a ``status`` field. By default 
the status is set to 4 (Development). If you change the status to 1 (Production) 
it is not longer possible to change the API endpoint through the backend. The 
following list describes each status

* ``4 = Development``
  Used as first status to develop a new API endpoint. It adds a "Warning" header 
  to each response that the API is in development mode.

* ``1 = Production``
  Used if the API is ready for production use. If the API transitions from 
  development to production all databases settings are copied into the route. 
  That means changing a schema or action will not change the API endpoint.

* ``2 = Deprecated``
  Used if you want to deprecate a specific version of the API. Adds a "Warning" 
  header to each response that the API is deprecated.

* ``3 = Closed``
  Used if you dont want to support a specific version anymore. Returns an error 
  message with a ``410 Gone`` status code

schema
------

The schema defines the format of the request and response data. It uses the 
JsonSchema format. Inside a schema it is possible to refer to other schema 
definitions by using the ``$ref`` key and the ``file`` protocol i.e. 
``file:///[file]``.

.. code-block:: json

    {
        "id": "http://acme.com/schema",
        "type": "object",
        "title": "schema",
        "properties": {
            "name": {
                "type": "string"
            },
            "author": {
                "$ref": "file:///author.json"
            },
            "date": {
                "type": "string",
                "format": "date-time"
            }
        }
    }

connection
----------

A connection provides a class which helps to connect to another service.

.. code-block:: yaml

    Acme-Mysql:
      class: Fusio\Adapter\Sql\Connection\Sql
      config:
        type: pdo_mysql
        host: localhost
        username: root
        password: test
        database: fusio

Please take a look at the :doc:`connection` overview to see all
available connection and return types. 
