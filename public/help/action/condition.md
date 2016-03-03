
## Condition

A condition is a expression which evaluates to a boolean value. Fusio uses the
Symfony ExpressionLanguage Component. Please visit the official [documentation] 
for detailed informations about the syntax.

### Syntax

The following list contains all available methods which can be used inside the 
condition.

 * `rateLimit`  
   * `getRequestsPerMonth()`  
     Returns the overall count of requests for the app of the current month
   * `getRequestsPerDay()`  
     Returns the overall count of requests for the app of the current day
   * `getRequestsOfRoutePerMonth()`  
     Returns the count of requests for the app and the route of the current 
     month
   * `getRequestsOfRoutePerDay()`  
     Returns the count of requests for the app and the route of the current day
 * `app`  
   * `isAnonymous()`  
     Returns a boolean whether the app is authenticated
   * `getId()`  
     Returns the id of the app or null
   * `getUserId()`  
     Returns the user id of the app or null
   * `getStatus()`  
     Returns the status of the app or null. For an authenticated request this is 
     currently always 1
   * `getName()`  
     Returns the name of the app or null
   * `getUrl()`  
     Returns the url of the app or null. This can also be empty for an 
     authenticated request since the field is not mandatory
   * `getAppKey()`  
     Returns the app key of the app
   * `getScopes()`  
     Returns all available scopes for the app. Note the available scopes for the
     app can differ from the scopes requested for the token
   * `hasScope(name)`  
     Returns whether the app has a specific token assigned
 * `user`  
   * `isAnonymous()`  
     Returns a boolean whether the user is authenticated
   * `getId()`  
     Returns the id of the user or null
   * `getStatus()`  
     Returns the status of the user or null
   * `getName()`  
     Returns the name of the user or null
 * `routeId`  
   Returns the integer id of the used route
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
   * `get(path)`  
     Returns a value of the request body. This can also be a path if the data
     structure is nested i.e. `author.name`

### Examples

Note while the condition is a powerful action you should consider to write a
custom action if there are to many specific cases. In the following a few 
examples:

* The current app has made less then 100 requests this month

        rateLimit.getRequestsPerMonth() < 100

* The request contains a GET parameter with <code>escalate=1</code> and the app 
  has made less then 10 requests today

        parameters.get('escalate') == 1 && rateLimit.getRequestsPerDay() < 10

[documentation]: http://symfony.com/doc/current/components/expression_language/introduction.html