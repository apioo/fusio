
Overview
========

This chapter gives a high level overview what the Fusio system provides and how
the application is structured. Lets take a look at the components which are 
provided by Fusio:

.. image:: _static/overview.png

Fusio API
---------

If you install a Fusio system it setups the default API with that it is possible
to manage the complete system. Because of that Fusio has some reserved paths 
which are needed by the system.

* ``/backend``
  Endpoints for configuring the system
* ``/consumer``
  Endpoints for the consumer i.e. register new accounts or create new apps 
* ``/doc``
  Endpoints for the documentation
* ``/authorization``
  Endpoints for the consumer to get i.e. information about the user itself and 
  to revoke an obtained access token
* ``/export``
  Endpoints to export the documentation into other formats i.e. swagger

All following apps are working with the API. Because of that it is also really 
easy to integrate Fusio into an existing system since you can call the endpoints 
from your application.

Backend App
-----------

.. image:: _static/backend.png

The backend app is the app where the administrator can configure the system. The 
app is located at ``/backend.htm``.

Developer App
-------------

.. image:: _static/developer.png

The developer app is designed to quickly setup an API programm where new 
developers can register and create/manage their apps. The app is located at 
``/developer/``.

Documentation App
-----------------

.. image:: _static/documentation.png

The documentation app simply provides an overview of all available endpoints. 
It is possible to export the API definition into other schema formats like i.e. 
Swagger. The app is located at ``/documentation/``.
