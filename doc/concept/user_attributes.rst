
User Attributes
===============

If you build an app and use the authorization system of Fusio you might want
to extend the default user table with additional columns i.e. first- and
last name (by default the user table contains only the most basic fields:
username, email and password). Fusio has a system called user attributes which
let you easily add arbitrary attributes to each user account. Therefor you need
to define the allowed attributes in the file ``configuration.php`` i.e.:

.. code-block:: text
    
    'fusio_user_attributes'   => [
        'first_name',
        'last_name',
    ],

Then it is possible to update the defined properties for each user by sending
an PUT request to the ``/consumer/account`` endpoint. This changes the
attributes of the current authenticated user.

.. code-block:: text
    
    {
      "email": "foo@bar.com",
      "attributes": {
        "first_name": "Sebastian",
        "last_name": "Bach",
      }
    }

This allows you to build a complete custom user profile page which contains all
fields for a user account which you need.
