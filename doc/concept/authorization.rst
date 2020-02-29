
Authorization
=============

If your API exposes protected endpoints you need a way to authorize your call.
At the core Fusio uses OAuth2 for authorization. This means you need to create
an access token to be able to request the API. This access token has always
an expire time adn can be revoked.

Simple
------

The most simple way to obtain an access token is to use the ``/consumer/login``
endpoint. If you need more control of your access token you should use the
Oauth2 endpoint to obtain an access token.

Request
^^^^^^^

.. code-block:: text
    
    POST /consumer/login
    Host: 127.0.0.1
    Content-Type: application/json
    
    {
      "username": "[username]",
      "password": "[password]"
    }

Response
^^^^^^^^

.. code-block:: text
    
    {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI5N2JkNDUzYjdlMDZlOWFlMDQxNi00YmY2MWFiYjg4MDJjZmRmOWZmN2UyNDg4OTNmNzYyYmU5Njc5MGUzYTk4NDQ3MDEtYjNkYTk1MDYyNCIsImlhdCI6MTQ5MTE2NzIzNiwiZXhwIjoxNDkxMTcwODM2LCJuYW1lIjoidGVzdCJ9.T49Af5wnPIFYbPer3rOn-KV5PcN0FLcBVykUMCIAuwI"
    }

OAuth2
------

Fusio provides an OAuth2 endpoint to obtain an access token. The endpoint
supports the following flows:

* Authorization Code
* Resource Owner Password Credentials
* Client Credentials

The following example shows how to obtain an access token using the client
credentials grant. Which grant you should use always depends on whether your
client is confidential or public. If your client is confidential this means you
can securely store a client id and secret.

Request
^^^^^^^

.. code-block:: text
    
    POST /authorization/token
    Host: 127.0.0.1
    Authorization: Basic NmM2MTM5NDUtOGQ1YS00YTBkLWI2NjAtMDlkZTVmYmRiNzUzOjMxZTA5M2Y5OGVhZDIyZWZjMjFiMzhhODdhMmE1YmQ3MWZjMTJiZWRlMzM3OWY1ZWFlNmM2ZjdkYTlkYWJjNWY=
    Content-Type: application/x-www-form-urlencoded
    
    grant_type=client_credentials&scope=authorization,backend

Response
^^^^^^^^

.. code-block:: text
    
    HTTP/1.1 200 OK
    Content-Type: application/json
    
    {
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC8xMjcuMC4wLjFcL3Byb2plY3RzXC9mdXNpb1wvcHVibGljIiwic3ViIjoiZTZjYTI4YWEtY2M4Ny01Y2JlLWEwMGEtYWM4YmNiZjgyMTU0IiwiaWF0IjoxNTUzMTA3OTM1LCJleHAiOjE1NTMyODA3MzUsIm5hbWUiOiJBZG1pbmlzdHJhdG9yIn0.9PYOaFkE0Qsnt5EUf-JF-73kBAiq8SVF495bjvo_eM0",
      "token_type": "bearer",
      "expires_in": 1553280735,
      "refresh_token": "65e95c8da122a0a5522f-534b054a029019548036c8253d591309247d2899223a6a7b-907deae7ff",
      "scope": "authorization"
    }

To extend an existing token you can use the refresh token grant i.e.:

Request
^^^^^^^

.. code-block:: text
        
    POST /authorization/token
    Host: 127.0.0.1
    Content-Type: application/x-www-form-urlencoded
    
    grant_type=refresh_token&refresh_token=65e95c8da122a0a5522f-534b054a029019548036c8253d591309247d2899223a6a7b-907deae7ff&client_id=6c613945-8d5a-4a0d-b660-09de5fbdb753&client_secret=31e093f98ead22efc21b38a87a2a5bd71fc12bede3379f5eae6c6f7da9dabc5f
