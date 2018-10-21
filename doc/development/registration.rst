
Registration
============

Fusio contains a registration system which can be used by you app to provide a
secure registration and social login. This chapter explains how you can embed
the registration system of Fusio in your app. In general it is important to note
that Fusio provides only APIs so you have to create the UI (i.e. a javascript
app) and call the fitting Fusio API endpoint.

Register
--------

At the registration process the user needs to provide a name, email and
password to create a new user account. In case you have configured a 
``RECAPTCHA_SECRET`` at your ``.env`` file Fusio checks also the ``captcha``
value. The captcha secret must be a google recaptcha secret.

If the user has provided the values at your UI you need to call the 
`/consumer/register`_ endpoint. If everything is valid Fusio creates a new user
account (which is deactivated) and sends a confirmation mail to the provided
email address.

The content of the activation mail can be customized at the settings panel of
the Fusio backend. There is a setting ``mail_register_body`` which can be
changed. If you are using the deploy mechanism you can also modify the
``resources/config.yaml`` file.

We host also a sample developer app which contains a `signup`_ form. 

Activate
--------

The activation mail contains a link to activate the account. The link must point
to your app, then your app needs to call the Fusio `/consumer/activate`_
endpoint to activate the account. In this way you can provide the user an UI
which is in your look and feel.

The activation link contains the token from the url which you must pass to the
endpoint. If everything is valid Fusio activates the user account.

Login
-----

The login endpoint uses a username and password and returns a token which can be
used for any subsequent API requests to authenticate the user. To login a user
you need to call the `/consumer/login`_ endpoint.

Optional you can also provide a list of scopes so that the user can only access
specific parts of your API.

Provider
--------

Besides the normal registration it is also possible to use a remote provider 
i.e. Google or Github to handle registration. Through this way users dont need
to create a separate account instead they can use an existing account to login.

To use such a social login you need to start the OAuth2 authentication flow and
call the `/consumer/provider/[provider]`_ endpoint if the user comes back from
the provider.

Fusio then calls the provider from the backend and checks whether this is a
valid user and gets additional user information. If everything went well the
method returns a token which can be used in any subsequent API calls. For more
information how to implement your own provider please take a look at the
:doc:`/concept/social_login` chapter.

.. _/consumer/register: http://demo.fusio-project.org/internal/#!/api/consumer/register
.. _signup: http://demo.fusio-project.org/developer/signup
.. _/consumer/activate: http://demo.fusio-project.org/internal/#!/api/consumer/activate
.. _/consumer/login: http://demo.fusio-project.org/internal/#!/api/consumer/login
.. _/consumer/provider/[provider]: http://demo.fusio-project.org/internal/#!/api/consumer/provider/:provider
