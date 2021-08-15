
# Development

In Fusio an action contains the business logic of your API. It i.e. inserts data to a database or returns specific data
for an endpoint. To give you a first impression the following action shows how to insert a todo entry:

```php
<?php

namespace App\Action\Todo;

use App\Model\Todo;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class Insert extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connector->getConnection('System');

        $body = $request->getPayload();
        $now  = new \DateTime();

        assert($body instanceof Todo);

        $connection->insert('app_todo', [
            'status' => 1,
            'title' => $body->getTitle(),
            'insert_date' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->response->build(201, [], [
            'success' => true,
            'message' => 'Insert successful',
        ]);
    }
}

```

At the code we get the `System` connection which returns a `\Doctrine\DBAL\Connection` instance. We have already
[many adapters](https://www.fusio-project.org/adapter) to connect to different services. Then we simply fire some
queries and return the response.

We have also a [CMS demo app](https://github.com/apioo/fusio-sample-cms) which is a headless CMS build with Fusio
which shows how to design and structure a more complex app.

## Folder structure

* `resources/config.yaml`  
  Contains common config values of your API
* `resources/connections.yaml`  
  Contains all available connections which can be used at an action. The `System` connection to the Fusio database is
  always available
* `resources/routes.yaml`  
  Contains all routes of your API. Each route points to a dedicated `yaml` file which contains all information about the
  endpoint
* `src/Action`  
  Contains all actions
* `src/Migrations`  
  Contains migrations which can be executed on a specific connection. To execute those migrations on the `System`
  connection you can run the following command: `php bin/fusio migration:migrate --connection=System`
* `src/Model`  
  Contains all models which are used at your actions. You can also automatically generate those models, please take a
  look at the `gen/` folder

## Deployment

To tell Fusio about all the routes, actions and connections which you define at the `yaml` files you need to run the
deploy command:

```
php bin/fusio deploy
```

This deploys the .yaml files at the `resource/` folder. It inserts the defined routes (i.e. `/todo`) and creates the
fitting schemas.

The todo API uses a simple table `app_todo` to store all entries. To create this table you also need to run the
migration files defined in `src/Migrations`.

```
php bin/fusio migration:migrate --connection=System
```

Now you should be able to visit the `/todo` endpoint.

The deployment system provides a way to store all metadata about the routes and schemas inside simple `.yaml` files. The
files are located at the `resources/` folder. Through the `php bin/fusio deploy` command it is then possible to insert
this metadata into a Fusio instance.

This has the advantage that you can simply rebuild your complete API on a new installation with simply running the
`deploy` command without the need to share a database. Also it has the big advantage that you can put your configuration
under version control.

## Connections

Fusio was designed to work with multiple database connections. By default it creates the `System` connection which works
on the same database where also Fusio is installed. If you want to place your app on a different database you can easily
create a new connection at the `resources/connections.yaml` file. There it is also possible to define connections to
various other systems. These connections can then be used in your action.

## Actions

The `src/` folder contains the action code which is executed if a request arrives at an endpoint. How Fusio executes
this code depends on the provided action string. The following engines are available. Please take a look at the
`doc/action/` folder to see example action implementations.

### PHP Class

```yaml
action: "App\\Todo\\CollectionAction"
```

If the action string is a PHP class Fusio tries to autoload this class through composer. The class must implement the
`Fusio\Engine\ActionInterface`. This is the most advanced solution since it is also possible to access services from the
DI container. In the following an example implementation:

```php
<?php

namespace App\Todo;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class CollectionAction extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        // @TODO handle request and return response

        return $this->response->build(200, [], [
            'message' => 'Hello World!',
        ]);
    }
}
```

### PHP File

```yaml
action: "${dir.src}/Todo/collection.php"
```

If the action points to a file with a `php` file extension Fusio simply includes this file. In the following an example
implementation:

```php
<?php
/**
 * @var \Fusio\Engine\ConnectorInterface $connector
 * @var \Fusio\Engine\ContextInterface $context
 * @var \Fusio\Engine\RequestInterface $request
 * @var \Fusio\Engine\Response\FactoryInterface $response
 * @var \Fusio\Engine\ProcessorInterface $processor
 * @var \Fusio\Engine\DispatcherInterface $dispatcher
 * @var \Psr\Log\LoggerInterface $logger
 * @var \Psr\SimpleCache\CacheInterface $cache
 */

// @TODO handle request and return response

$response->build(200, [], [
    'message' => 'Hello World!',
]);
```

### HTTP Url

```yaml
action: "http://foo.bar"
```

If the action contains an `http` or `https` url the request gets forwarded to the defined endpoint. Fusio automatically
adds some additional headers to the request which may be used by the endpoint i.e.:

```http
X-Fusio-Route-Id: 72
X-Fusio-User-Anonymous: 1
X-Fusio-User-Id: 4
X-Fusio-App-Id: 3
X-Fusio-App-Key: 1ba7b2e5-fa1a-4153-8668-8a855902edda
X-Fusio-Remote-Ip: 127.0.0.1
```

### Static file

```yaml
action: "${dir.src}/static.json"
```

If the action points to a simple file Fusio will simply forward the content to the client. This is helpful if you want
to build fast an sample API with dummy responses.

## Migrations

Fusio integrates the [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html) system to easily
make database schema changes on different connections. Fusio determines the connection based on the `--connection`
option. If you use the `System` connection (which is created by default) it will work on the same database where Fusio
is installed but it will ignore all `fusio_` tables.

```
php bin/fusio migration:migrate --connection=System
```

The migration classes are placed inside `src/Migrations` folder. The folder below must be the name of your connection.
Through this way you can easily define multiple migrations for different connections.

To create a new migration class you can simply run the `generate` command:

```
php bin/fusio migration:generate --connection=System
```

For more information please take a look at the website of [Doctrine Migrations](https://www.doctrine-project.org/projects/migrations.html).
The following commands are available:

```
migration:execute     Execute a single migration version up or down manually.
migration:generate    Generate a blank migration class.
migration:latest      Outputs the latest version number
migration:migrate     [install] Execute a migration to a specified version or the latest available version.
migration:status      View the status of a set of migrations.
migration:up-to-date  Tells you if your schema is up-to-date.
migration:version     Manually add and delete migration versions from the version table.
```
