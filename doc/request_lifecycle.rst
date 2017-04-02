
Request lifecycle
=================

To get a better understanding how Fusio works lets take a look at the following 
flow chart:

.. image:: _static/backend_flow.png

If a request arrives Fusio looks at the routes table to find the fitting route. 
The route has all information which request methods are allowed, how the request 
and response schema is and what action to execute. The schema is a specification 
of the request or response data in the JSONSchema format. It is not required to 
specify a schema for your endpoint but it is recommended since the 
documentation is based on the schema. If the request arrives at the action the 
business logic of the endpoint is executed. A action can use connections in 
order to connect to a remote service and execute a specific task. In case the 
route is protected the consumer has to authorize the request through OAuth2. 
Therefor he needs a consumer key and secret which he can obtain through an app. 
Each app has specific scopes assigned so that the app can only access the 
specific parts of the API.
