
Backend
=======

This chapter contains all informations how to configure the backend. To get a 
better understanding how Fusio works lets take a look at the following flow 
chart:

.. image:: _static/fusio_flow.png

If a request arrives Fusio looks at the routes table to find the fitting route. 
The route has all informations what request method is allowed, how the request 
and response schema is and what action to execute. The schema is a specification 
of the request or response data in the JSONSchema format. It is not required to 
specifiy a schema for your endpoint but it is recommended since the 
documentation is based on the schema. If the request arrives at the action the 
business logic of the endpoint is executed. A action can use connections in order to 
connect to a remote service and execute a specific task. In case the route is 
protected the consumer has to authorize the request through OAuth2 therefor he 
needs a consumer key and secret which he can obtain through an app. Each app has 
specific scopes assigned so that the app can only access the wanted parts of the 
API. More informations about the specific areas at the following pages (these
pages are extracted from the online help which are also available inside the 
backend app):

.. toctree::

   backend/routes
   backend/action
   backend/schema
   backend/connection
   backend/app
   backend/scope
   backend/user
