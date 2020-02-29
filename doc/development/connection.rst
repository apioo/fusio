
Connection
==========

Inside an action you can use the ``connector`` to obtain a configured
connection. The following list contains connection classes which you can use. 
Note some connections depend on PHP extensions or other client libraries, you
have to install the fitting adapter in order to use the connection. Take a look
at the `adapter page`_ for an overview of available adapters.

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

.. _adapter page: https://www.fusio-project.org/adapter
