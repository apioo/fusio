
## Template

Fusio uses a customized version of the template engine Twig. Detailed 
informations about the syntax can be found at the offical [documentation]. This
documentation describes the Fusio specific features.

### Filter

#### JSON

The JSON filter converts a value into a JSON presentation. It is possible to 
convert the incoming request back into JSON i.e.

    {{ request.body|json }}

#### Prepare

The prepare filter should be used to generate save SQL queries. If you have an 
endpoint which returns informations for an specific id you could write the 
following safe SQL statment i.e.

    SELECT title, updated FROM acme_news WHERE id = {{ request.parameters.get('foo')|prepare }}

### Parameters

Inside a template you can access all parameters from the environment. The 
following list contains all available parameters.

 * `request`  
   * `uriFragments`  
     * `get(name)`  
       Returns the uri fragment with the given name
     * `has(name)`  
       Returns a boolean whether the given uri fragment is available
   * `parameters`  
     * `get(name)`  
       Returns the GET parameter with the given name
     * `has(name)`  
       Returns a boolean whether the given GET parameter is available
   * `body`  
     Returns the parsed body
 * `context`  
   * `routeId`  
   * `app`  
     * `id`  
       Returns the id of the app or null
     * `userId`  
       Returns the user id of the app or null
     * `status`  
       Returns the status of the app or null. For an authenticated request this is 
       currently always 1
     * `name`  
       Returns the name of the app or null
     * `url`  
       Returns the url of the app or null. This can also be empty for an 
       authenticated request since the field is not mandatory
     * `appKey`  
       Returns the app key of the app
     * `scopes`  
       Returns all available scopes for the app. Note the available scopes for the
       app can differ from the scopes requested for the token
   * `user`  
     * `id`  
       Returns the id of the user or null
     * `status`  
       Returns the status of the user or null
     * `name`  
       Returns the name of the user or null
   * `get(path)`  
     Returns a value of the request body. This can also be a path if the data
     structure is nested i.e. `author.name`

[documentation]: http://twig.sensiolabs.org/doc/templates.html
