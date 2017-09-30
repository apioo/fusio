
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

The following list contains connection classes which you can use. Note some 
connections depend on PHP extensions or other client libraries, you have to
install the fitting adapter in order to use the connection. Take a look at the
http://www.fusio-project.org/adapter website for an overview of available 
adapters.

Sql
^^^

Connects to a SQL database using the doctrine DBAL library.

Class
  ``Fusio\Adapter\Sql\Connection\Sql``
Return
  ``Doctrine\DBAL\Connection``
Website
  http://www.doctrine-project.org/projects/dbal.html
API
  http://www.doctrine-project.org/api/dbal/2.5/class-Doctrine.DBAL.Connection.html

**config**

``type``
  The driver which is used to connect to the database

  * ``pdo_mysql`` = MySQL
  * ``pdo_pgsql`` = PostgreSQL
  * ``sqlsrv`` = Microsoft SQL Server
  * ``oci8`` = Oracle Database
  * ``sqlanywhere`` = SAP Sybase SQL Anywhere
``host``
  The IP or hostname of the database server
``username``
  The name of the database user
``password``
  The password of the database user
``database``
  The name of the database which is used upon connection

MongoDB
^^^^^^^

Connects to a MongoDB using the official MongoDB library. Note this requires
the PHP ``mongodb`` extension.

Class
  ``Fusio\Adapter\Mongodb\Connection\MongoDB``
Return
  ``MongoDB\Database``
Website
  https://github.com/mongodb/mongo-php-library
API
  https://docs.mongodb.com/php-library/master/reference/class/MongoDBDatabase/

**config**

``url``
  The url must have the following format ``mongodb://[username:password@]host1[:port1][,host2[:port2:],...]/db``
``options``
  It is possible to provide option parameters. The options must be url encoded i.e. ``connect=1&fsync=1``
``database``
  The name of the database which is used upon connection

HTTP
^^^^

Uses the Guzzle library to send HTTP requests.

Class
  ``Fusio\Adapter\Http\Connection\Http``
Return
  ``GuzzleHttp\Client``
Website
  http://docs.guzzlephp.org/en/latest/

**config**

``url``
  HTTP base url
``username``
  Optional username for authentication
``password``
  Optional password for authentication
``proxy``
  Optional HTTP proxy

AMQP
^^^^

Provides a client to send messages to a RabbitMQ.

Class
  ``Fusio\Adapter\Amqp\Connection\Amqp``
Return
  ``PhpAmqpLib\Connection\AMQPStreamConnection``
Website
  https://github.com/php-amqplib/php-amqplib

**config**

``host``
  The IP or hostname of the RabbitMQ server
``port``
  The port used to connect to the AMQP broker. The port default is 5672
``user``
  The login string used to authenticate with the AMQP broker
``password``
  The password string used to authenticate with the AMQP broker
``vhost``
  The virtual host to use on the AMQP broker

Beanstalk
^^^^^^^^^

Provides a client to send messages to a Beanstalkd.

Class
  ``Fusio\Adapter\Beanstalk\Connection\Beanstalk``
Return
  ``Pheanstalk\Pheanstalk``
Website
  https://github.com/pda/pheanstalk

**config**

``host``
  The IP or hostname of the Beanstalk server
``port``
  Optional the port of the Beanstalk server

Cassandra
^^^^^^^^^

Connects to a Cassandra database using the official PHP library. Requires the
``cassandra`` PHP extension.

Class
  ``Fusio\Adapter\Cassandra\Connection\Cassandra``
Return
  ``Cassandra\Session``
Website
  https://github.com/datastax/php-driver
API
  http://datastax.github.io/php-driver/api/Cassandra/interface.Session/

**config**

``host``
  Configures the initial endpoints. Note that the driver will automatically discover and connect to the rest of the cluster
``port``
  Specify a different port to be used when connecting to the cluster
``keyspace``
  Optional keyspace name

Elasticsearch
^^^^^^^^^^^^^

Connects to a Elasticsearch database using the official PHP library.

Class
  ``Fusio\Adapter\Elasticsearch\Connection\Elasticsearch``
Return
  ``Elasticsearch\Client``
Website
  https://github.com/elastic/elasticsearch-php

**config**

``host``
  Comma separated list of elasticsearch hosts i.e. ``192.168.1.1:9200,192.168.1.2``

Memcache
^^^^^^^^

Uses the native PHP ``memcached`` extension to connect to a memcache server.

Class
  ``Fusio\Adapter\Memcache\Connection\Memcache``
Return
  ``Memcached``
Website
  http://php.net/manual/de/book.memcached.php

**config**

``host``
  Comma seperated list of [ip]:[port] i.e. ``192.168.2.18:11211,192.168.2.19:11211``

Neo4j
^^^^^

Connects to a Neo7j graph database using the official PHP library.

Class
  ``Fusio\Adapter\Neo4j\Connection\Neo4j``
Return
  ``GraphAware\Neo4j\Client\ClientInterface``
Website
  https://github.com/graphaware/neo4j-php-client

**config**

``uri``
  URI of the connection i.e. ``http://neo4j:password@localhost:7474``

SOAP
^^^^

Provides a client to send SOAP requests.

Class
  ``Fusio\Adapter\Soap\Connection\Soap``
Return
  ``SoapClient``
Website
  http://php.net/manual/de/class.soapclient.php

**config**

``wsdl``
  Location of the WSDL specification
``location``
  Required if no WSDL is available
``uri``
  Required if no WSDL is available
``version``
  Optional SOAP version

  * ``1`` = SOAP 1.1
  * ``2`` = SOAP 1.2
``username``
  Optional username for authentication
``password``
  Optional password for authentication

migration
---------

The migration key can contain an array of files per connection. The files are
executed once on deployment. At the moment migrations are only supported for SQL
connections.

.. code-block:: yaml

    Default-Connection:
      - resources/sql/v1_schema.php

**Note: If you migrate a schema to a specific database the migration tool will
delete all tables from the database to adjust the tables according to the 
defined schema This means all tables which are not defined in the migration file
will be deleted.**
