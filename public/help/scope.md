
## Scope

A scope describes the right to access specific routes and request methods. Each
user account has assigned a set of scopes. If an app is created the app has by 
default the scopes which are assigned to the user of the app. For the user it is 
then possible to request only a subset of the scopes when accessing the API.
This has security benefits since the access token can only request specific 
parts of the API.

