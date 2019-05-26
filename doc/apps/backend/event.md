
## Event

Fusio has an event system which helps to build a pub/sub system. This means
consumer of your API can subscribe to specific events. Inside your API endpoint
you can then trigger such an event. Fusio will then send the payload to each
subscriber in the background.

### Installation

To enable Fusio to send messages in the background you need to setup a cronjob
which executes the HTTP requests. The cronjob must execute the `event:execute`
command i.e.:

```
php /bin/fusio event:execute
```

### Subscribe

To subscribe for such an event the user needs to send a HTTP POST request to the
`/consumer/subscription` endpoint with the following payload:

```json
{
  "event": "my_event",
  "endpoint": "http://my-app.com/callback"
}
```

### Publish

To publish an event you need to use the dispatcher to create an event. I.e. the
following action shows how to dispatch data to the event `my_event` which then
will send the provided data JSON encoded to the subscribed endpoints.

```php
<?php

namespace App\Todo;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class Collection extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        // dispatch my event
        $this->dispatcher->dispatch('my_event', [
            'foo' => 'bar'
        ]);
    }
}
```

### Callback

Fusio will send the following HTTP POST request to every subscribed endpoint. In
case the endpoint returns a non successful status code Fusio will try to resend
the message up to 3 times.

```
POST /callback HTTP/1.1
Host: my-app.com
Content-Type: application/json
User-Agent: Fusio/4.0.2@916a81045349cc0e149873b5b794777bb5f29a30

{"foo": "bar"}
```
