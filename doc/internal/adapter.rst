
Adapter
=======

An adapter is a composer package which provides classes to extend the 
functionality of Fusio. Through an adapter it is i.e. possible to provide 
custom action/connection classes or to install predefined routes for an existing
system. Our `website`_ lists every available composer package which has the 
``fusio-adapter`` keyword defined in the ``composer.json`` file.

The adapter needs to require the ``fusio/engine`` package and must have an 
adapter class which implements the ``Fusio\Engine\AdapterInterface`` interface. 
This interface has a method ``getDefinition`` which returns an absolute path to 
a ``adapter.json`` definition file. This definition contains all information for 
Fusio how to extend the system. The adapter can be installed through the 
register command:

.. code-block:: text

    php bin/fusio system:register "Acme\System\Adapter"

Provider
--------

User
^^^^

Interface: ``Fusio\Engine\User\ProviderInterface``

Describes a remote identity provider which can be used to authorize an user
through a remote system so that the developer dont need to create an account.
Usually this is done through OAuth2, which has the following flow:
 
- The App redirects the user to the authorization endpoint of the remote
  provider (i.e. Google)
- The user authenticates and returns via redirect to the App
- The App calls the API endpoint and provides the fitting data to Fusio
- If everything is ok Fusio will get additional information and create a new
  account

Please take a look at the `Github`_ provider for an example implementation.

.. code-block:: json

    {
      "userClass": ["Acme\System\User\Provider"]
    }

Payment
^^^^^^^

Interface: ``Fusio\Engine\Payment\ProviderInterface``

Describes a payment provider which can be used to execute payments. Through
the developer app the user has the possibility to buy points which can be
used to call specific routes which cost points. To buy those points Fusio
uses these payment providers to execute a payment. Usually the flow is:

- App calls the API endpoint to prepare a specific product, it provides an
  plan and a return url. The call returns an approval url
- App redirects the user to the approval url. The user has to approve the
  payment at the payment provider
- User returns to the App, the url contains the id of the transaction so the
  app can call the API endpoint to get details about the transaction
- If everything is ok Fusio will credit the points to the user so that he can
  start calling specific endpoints

Please take a look at the `Paypal`_ provider for an example implementation.

.. code-block:: json

    {
      "paymentClass": ["Acme\System\Payment\Provider"]
    }

Routes
^^^^^^

Interface: ``Fusio\Engine\Routes\ProviderInterface``

Preconfigured route provider which helps to create automatically schemas,
actions and routes for the user. This can be used to create complete
applications.

Please take a look at the `SQL-Table`_ provider for an example implementation.

.. code-block:: json

    {
      "routesClass": ["Acme\System\Routes\Provider"]
    }

Testing
-------

If you build an adapter it is recommend to build a test case which extends the
``Fusio\Engine\Test\AdapterTestCase`` Test-Case. This test case checks whether
the `definition.json` is valid and contains only plausible values.

Schema
------

Please take a look at the `JsonSchema`_ to see all options and to validate an
existing definition.json file.


.. _Github: https://github.com/apioo/fusio-impl/blob/master/src/Provider/User/Github.php
.. _Paypal: https://github.com/apioo/fusio-adapter-paypal/blob/master/src/Provider/Paypal.php
.. _SQL-Table: https://github.com/apioo/fusio-adapter-sql/blob/master/src/Routes/SqlTable.php
.. _JsonSchema: https://github.com/apioo/fusio-engine/blob/master/src/Test/definition_schema.json
.. _website: https://www.fusio-project.org/adapter
