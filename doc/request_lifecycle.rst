
Request lifecycle
=================

The following image illustrates the Fusio request lifecycle:

.. image:: _static/backend_flow.png

Route
-----

If a request arrives Fusio looks at the routes table to find the fitting route. 
The route has all information which request methods are allowed, how the request 
and response schema is and what action to execute. If the route is protected
the request must contain an ``Authorization`` header with a fitting bearer token.
At this stage Fusio also checks the rate limits and rejects the request in case 
the user has reached the request limit.

Schema
------

The schema is a specification of the request or response data in the JSONSchema 
format. It is not required to specify a schema for your endpoint but it is 
recommended since the documentation is based on the schema. If the provided
data does not validate against the schema Fusio throws an error.

Action
------

If the request arrives at the action the request payload is already validated
according to the schema. The action executes now the business logic of the 
endpoint. A action can use connections in order to connect to a remote service 
and execute a specific task. 
