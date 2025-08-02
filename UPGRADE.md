
# Upgrade

This document describes how you can upgrade a Fusio installation to the next major version. Before upgrading the first
step is to make a backup of your existing Fusio database to be able to restore your database in case of a problem.
We recommend to upgrade each major version step by step i.e. if you have installed Fusio 3.x you should upgrade to 4.x
before upgrading to 5.x etc.

## Upgrade to 6.x

Fusio 6.x requires PHP 8.3 as minimum version.

The upgrade to the 6.x version has one breaking change which could affect you,
otherwise the upgrade should be smooth. In the 6.x version we have moved
the `/backend/database` endpoint under the `/backend/connection` endpoint.
In case you use those endpoints, you need to adjust them accordingly, the schema has
otherwise not changed.

### API changes

| Old                                                      | New                                                                 |
|----------------------------------------------------------|---------------------------------------------------------------------|
| `/backend/database/:connection_id`                       | `/backend/connection/:connection_id/database`                      |
| `/backend/database/:connection_id/:table_name`           | `/backend/connection/:connection_id/database/:table_name`          | 
| `/backend/database/:connection_id/:table_name/rows`      | `/backend/connection/:connection_id/database/:table_name/rows`     | 
| `/backend/database/:connection_id/:table_name/rows/:id`  | `/backend/connection/:connection_id/database/:table_name/rows/:id` | 

----

## Upgrade to 5.x

Fusio 5.x requires PHP 8.2 as minimum version. 

The upgrade to the 5.x version has no special requirements. You only need to download the current Fusio version from
[GitHub](https://github.com/apioo/fusio/tags) and place it in a clean new folder. Then configure the database
credentials of your old Fusio installation at the `.env` file. Copy also all existing env settings from your old
installation. Now you can run the `migrate` command which transforms your existing database to the new schema.

### Schema changes

* The `tenant_id` column was added to most tables
* `fusio_app_token` was renamed to `fusio_token`
* `fusio_event_subscription` was renamed to `fusio_webhook`
* `fusio_event_response` was renamed to `fusio_webhook_response`

### Worker redesign

With this release we have changed the worker system. If you use a worker your code most likely needs adjustment since
the worker API has changed. In previous version we used the [Apache Thrift](https://thrift.apache.org/) RPC system
to handle the Fusio worker communication. With the new version we have moved to a simple REST API, which also allows us
to run a worker in a serverless environment like AWS Lambda. You need to consider the following changes:

* The `request` and `context` API has changed, you can take a look at the [API specification](https://app.typehub.cloud/d/fusio/worker) to see all new properties
* Java worker now executes Groovy/Java code instead of a plain Java class
* PHP worker the namespace of the request/context has changed to `Fusio\Worker\ExecuteRequest`/`Fusio\Worker\ExecuteContext`

We have also updated the worker documentation which should provide some new examples:

* [Java](https://docs.fusio-project.org/docs/backend/api/action/worker-java)
* [Javascript](https://docs.fusio-project.org/docs/backend/api/action/worker-javascript)
* [PHP](https://docs.fusio-project.org/docs/backend/api/action/worker-php)
* [Python](https://docs.fusio-project.org/docs/backend/api/action/worker-python)

----

## Upgrade to 4.x

Fusio 4.x requires PHP 8.1 as minimum version.

Download the current Fusio version from [GitHub](https://github.com/apioo/fusio/tags) and place it in a clean new
folder. Then configure the database credentials of your old Fusio installation at the `.env` file, in the new version we
have only a single `APP_CONNECTION` parameter. Copy also all existing env settings from your old installation. Now you
can run the `migrate` command (which was previously the `install` command), which transforms your existing database to
the new schema.

If this was successful you should be already able to login to the new Fusio backend. In the new 4.x version the route
entity was replaced by an operation entity. This means at this stage all your previously defined routes are currently
not migrated.

The next step depends on your Fusio setup, if you use the deploy mechanism and you have all your route configurations in
`.yaml` files, then it is recommended to only migrate those yaml files and then call the `deploy` command, so that Fusio
automatically creates the operations in the new format. If you dont use the deloy mechanism you can run the upgrade
command to migrate all entries from the `fusio_routes` table to the new `fusio_operation` table.

### Upgrade command

The upgrade command can be executed through the following command:

```
php bin/fusio system:upgrade
```

This command basically reads all entries from the old `fusio_routes`, `fusio_routes_method` and `fusio_routes_response`
tables and migrates them to the new `fusio_operation` table.

### Deploy files

In your `.fusio.yml` file you need to add an operation key.

```yaml
operation: !include resources/operation.yaml
```

The `operation.yaml` file then contains all operations. Previosuly in the routes files the key was the path to a
resource, now the key is the name of an operation i.e.

```yaml
"explore.getAll": !include resources/operations/explore/collection.yaml
"document.getAll": !include resources/operations/document/collection.yaml
"document.get": !include resources/operations/document/entity.yaml
"document.create": !include resources/operations/document/create.yaml
"document.update": !include resources/operations/document/update.yaml
"document.delete": !include resources/operations/document/delete.yaml
```

The detail file of an operation describes all operation properties. The important difference is that at the route detail
file you have described multiple request methods for an endpoint i.e. `GET` and `POST` now you have always one operation
for a specific request method.

```yaml
scopes: ["document"]
stability: 1
description: "Creates a new document"
httpMethod: POST
httpPath: "/document/:user"
httpCode: 201
incoming: "App\\Model\\DocumentCreate"
outgoing: "App\\Model\\Message"
throws:
  500: "App\\Model\\Message"
action: "App\\Action\\Document\\Create"
```

### Migrate actions

In the 4.x version we have changed our internal DI container to the [Symfony DIC](https://github.com/symfony/dependency-injection)
component. In general your action should still work but there is a new `resources/container.php` file which describes
how to load your services.

It is now recommended to only implement the `Fusio\Engine\ActionInterface` instead of the `Fusio\Engine\ActionAbstract`
at your action. Through this you can better control the exact dependencies which are required for your action. The
`ActionAbstract` is only needed if you want to select and configure the action at the backend.
