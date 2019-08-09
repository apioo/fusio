
JsonRPC
=======

Fusio contains a JsonRPC endpoint at ``/export/jsonrpc`` which can be used to
execute directly a specific method. The method name is identified by the
"Operation-Id" of each route method. Through the endpoint it is possible to
execute multiple methods within a single request.

In case your method needs authorization you need to add an authorization header
i.e. ``Authorization: Bearer [token]`` to the RPC call.

Example
-------

The following code shows a simple example how to talk to the JsonRPC endpoint
using a `JsonRPC client`_

.. code-block:: json

    <?php
    
    require __DIR__ . '/vendor/autoload.php';
    
    use Graze\GuzzleHttp\JsonRpc\Client;
    
    $client = Client::factory('http://myapi.com/export/jsonrpc');

    $responses = $client->sendAll([
        $client->request(1, 'my.operation.id', [
            'uriFragments'=> ['foo' => 'bar'],
            'parameters'=> ['foo' => 'bar'],
            'headers'=> ['foo' => 'bar'],
            'body'=> ['foo' => 'bar'],
        ]),
    ]);
    
    foreach ($responses as $resp) {
        var_dump((string) $resp->getBody());
    }

.. _JsonRPC client: https://github.com/graze/guzzle-jsonrpc
