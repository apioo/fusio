
Overview
========

About
-----

Fusio is an open source API management platform which helps to build and manage 
RESTful APIs. We think that there is a huge potential in the API economy. 
Whether you need an API to expose your business functionality, build micro 
services or to develop One-Page web applications or Mobile-Apps. Because of this 
we think that Fusio is a great tool to simplify building such APIs. More 
information on http://www.fusio-project.org/

Features
--------

Fusio covers all important aspects of the API lifecycle so you can concentrate
on building the actual business logic of your API.

* **Versioning**

  It is possible to define different versions of your endpoint. A concrete 
  version can be requested through the ``Accept`` header i.e. ``application/vnd.acme.v1+json``
* **Documentation**

  Fusio generates automatically a documentation of the API endpoints based on 
  the provided schema definitions.
* **Authorization**

  Fusio uses OAuth2 for API authorization. Each app can be limited to scopes to 
  request only specific endpoints of the API.
* **Analytics**

  Fusio monitors all API activities and shows them on a dashboard so you always 
  know what is happening with your API. 
* **Rate limiting**

  It is possible to limit the requests to a specific threshold.

Fusio provides already many actions to handle common use cases. I.e. it is 
possible to execute SQL queries against a database or send data into a message 
queue. It is also very easy to build a customized action. Fusio provides also an 
[adapter system](http://www.fusio-project.org/adapter) through this it is 
possible to share those actions via composer.

System
------

This section gives a high level overview what the Fusio system provides and how
the application is structured. Lets take a look at the components which are 
provided by Fusio:

.. image:: _static/overview.png

API
^^^^

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

Apps
----

All following apps are working with the API. Because of that it is also really 
easy to integrate Fusio into an existing system since you can call the endpoints 
from your application.

Backend
^^^^^^^

.. image:: _static/backend.png

The backend app is the app where the administrator can configure the system. The 
app is located at ``/fusio/``.

Developer
^^^^^^^^^

.. image:: _static/developer.png

The developer app is designed to quickly setup an API programm where new 
developers can register and create/manage their apps. The app is located at 
``/developer/``.

Documentation
^^^^^^^^^^^^^

.. image:: _static/documentation.png

The documentation app simply provides an overview of all available endpoints. 
It is possible to export the API definition into other schema formats like i.e. 
Swagger. The app is located at ``/documentation/``.
