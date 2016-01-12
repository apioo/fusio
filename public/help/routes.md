
## Routes

A route is the rule which redirects the incoming request to an action. If a 
request arrives the first route which matches is taken. In order to be able to
evolve an API it is possible to add multiple version for the same route. For 
each version it is possible to specify the allowed request methods. Each method
describes the request and response schema and the action which is executed upon 
request. If a request method is public it is possible to request the API 
endpoint without an access token.

### Path

The path can contain variable path fragments. It is possible to access these 
variable path fragments inside an action. The following list describes the 
syntax.

* `/news`
  No variable path fragment only the request to `/news` matches this route

* `/news/:news_id`
  Simple variable path fragment. This route matches to any value except a slash.
  I.e. `/news/foo` or `/news/12` matches this route

* `/news/$year<[0-9]+>`
  Variable path fragment with a regular expression. I.e. only `/news/2015` 
  matches this route

* `/file/*path`
  Variable path fragment which matches all values. I.e. `/file/foo/bar` or 
  `/file/12` matches this route

### Status

The status affects the behaviour of the API endpoint. The following list 
describes each status

* `Development`
  Used as first status when you start develop a new API endpoint. Adds a 
  "Warning" header that the API is in development mode to each response.

* `Production`
  Used if the API is ready for production use. In this status it is not possible 
  to change the API. That means you can also not change any depending schema or 
  action.

* `Deprecated`
  Used if you want to deprecate a specific version of the API. Adds a "Warning" 
  header that the API is deprecated to each response.

* `Closed`
  Used if you dont want to support a specific version anymore. Returns an error 
  message with a `410 Gone` status code

### Action

The action contains the business logic of your API endpoints. It i.e. selects
or inserts entries from a database or pushes a new entry to an message queue.

