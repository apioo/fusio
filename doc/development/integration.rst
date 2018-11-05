
Integration
===========

Fusio is often used to create a REST API beside an existing web app. This
chapter describes best practices how you can integrate your app without ignoring
the business logic.

At first we should distinguish between read and write requests. A read
request is a request which does not modify the state (database) of your app. For
this case you can also connect directly to your app database. A write request
modifies the state (database) of your app i.e. it creates a new record. In this
case you most likely want to run the business logic of your app so that all data
gets validated and all depending mechanisms are executed. For this case there
are multiple ways to run your business logic:

HTTP
^^^^

Your app provides an internal API which gets called by Fusio. In this case your
action uses a HTTP connection to call the internal API of your app. The
internal API also does not need to have a great design since the user only faces
the Fusio endpoints. I.e. you could create a simple ``api.php`` script which
bootstraps your app and invokes a specific method.

RPC
^^^

Your app provides an RPC service (i.e. Apache Thrift or GRPC) which can be
called by Fusio. This has also the advantage that the performance is much better
then an internal HTTP API because modern RPC services mostly serialize the data
into an optimized binary format instead of JSON or XML.

Message-Queue
^^^^^^^^^^^^^

Your app provides a message queue which Fusio can use. This has also great
performance but it is a unidirectional connection, this means the message queue
can never return a response to Fusio. In most cases the response message must be
defined in the action. Fusio has connections to connect to a AMQP or Beanstalk
message queue.

SQL
^^^

In case you have no additional business logic which needs to be executed you can
also directly connect to the database and insert a new entry.

Include
^^^^^^^

Another (but not recommended) solution is to include your app bootstrap code
inside an action. This is possible but then you are mixing the context between
your existing app and Fusio. In most cases it is recommended to use one of the
approaches described above. But for some small apps it might be also feasible
since this has basically no performance penalty.
