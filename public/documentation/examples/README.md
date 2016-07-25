
This folder contains example YAML files for the API. This is useful for the user
to show code snippets or HTTP request/response how to use the API. If examples
are enabled in the config evid tries to load for each endpoint also the example
file. The file has the name of the api where slashes are replaced with an
underscore. I.e. for the path `/backend/action` the app would try to load the 
file `examples/backend_action.yaml`. In the following an example how the file is 
structured:

```yaml
GET:
  http:
    request: |
      GET /backend/action HTTP/1.1
      Accept: application/json
    response: |
      HTTP/1.1 200 OK
      Content-Type: application/json
      
      {"foo": "bar"}
  php:
    retrieve: |
      <?php
      $response = $client->getActions();
```

At the first level there must be a request method. On the next level is the 
language in which this example is. This key is also passed to the highliter 
(https://highlightjs.org/) as language. On the next level you can choose as key 
an arbitrary string to describe the sample and as value the actual example.
