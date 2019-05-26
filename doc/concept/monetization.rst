
Monetization
============

Fusio helps you to monetize your API. It has a concept of points which each user
can buy. A user can then spend those points by calling specific routes which
cost a specific amount of points. The API developer can simply add a cost to
every route and request method.

Installation
------------

At first you need to create a plan at the Fusio backend. A plan has a name, a
specific amount of points and a price assigned.

Then you need to configure a payment provider. For this you need to include i.e.
the paypal adapter which configures paypal as payment provider.

.. code-block:: json
    
    composer require fusio/adapter-paypal
    php bin/fusio system:register "Fusio\Adapter\Paypal\Adapter"

Then you need to create a new connection at the Fusio backend. This connection
must be named "paypal" and you need to provide your app credentials.

Flow
----

If a user of your API wants to obtain points he has to use a configured payment
provider. To start the payment process your app has to send a POST request to
the ``/consumer/transaction/prepare/paypal`` endpoint (in this example we use
paypal as provider) with the following payload:

.. code-block:: json
    
    {
      "planId": 1,
      "returnUrl": "http://my-app.com/payment/return?transaction_id={transaction_id}"
    }

The ``planId`` is the id of a plan which was configured at the backend. The
return url is the url of your app where the user returns after the payment was
completed. If everything is valid the endpoint returns an approval url of the
payment provider:

.. code-block:: json
    
    {
      "approvalUrl": ""
    }

Your app has to simply redirect the user to this approval url. Then the user
authenticates at the payment provider and approves the payment. Then the user
gets redirected to the ``/consumer/transaction/execute/{transaction_id}``
endpoint where Fusio checks whether the payment was accepted. If yes Fusio
credits the amount of points to the user.

Then it redirects the user to the return url which was provided in the initial
prepare call. You app can then lookup the status of the transaction and display
a fitting message.

Implementation
--------------

It is also easy to implement a custom payment provider. It is important that the
provider supports a redirect based flow. It is currently not possible to simply
enter the credit card number. To create a new payment provider you need to
create a class which implements the ``Fusio\Engine\Payment\ProviderInterface``
