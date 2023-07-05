
<p align="center">
    <a href="https://www.fusio-project.org/" target="_blank"><img src="https://www.fusio-project.org/img/fusio_64px.png"></a>
</p>

# About

Fusio is an open source API management platform which helps to create innovative API solutions. We know that building a
complete API product is no easy task, because of this we have created Fusio which supports you in every aspect of the
API life cycle:  

* __Building__  
  Fusio provides many ways to build new API endpoints, it can proxy existing APIs, directly work with external databases
  or files and it is also possible to implement custom business logic 
* __Documentation__  
  Fusio contains a schema store which can be used to describe the request and response payloads of each endpoint. Those
  schemas are then used to generate i.e. an OpenAPI specification or also client SDKs.
* __Monetization__  
  Fusio provides a simple way to monetize your existing API by using an payment provider like Stripe.
* __Onboarding__  
  Fusio provides a ready to use developer portal where external developers can register to consume your API.
* __Integration__  
  Fusio contains a powerful SDK generator which can automatically generate high quality and ready to use code so that
  you users can easily consume your API.
* __Monitoring__  
  Fusio provides an intuitive backend where you can monitor important aspects of your API.

## Use-Cases

Fusio can help you with the following use cases:

* __API-Product__  
  Fusio helps you to create a great API product, besides building an API it provides a developer portal where developers
  can register and a way to monetize your API
* __API-Gateway__  
  Fusio can be used as gateway to your internal API and microservices. It handles all common features like
  Authorization, Rate-Limiting and Schema-Validation
* __SPA-Backend__  
  Fusio can be used as backend to build SPAs using popular Javascript-Frameworks like i.e. Angular, React or Vue. It
  provides a powerful code generator which can automatically generate an SDK for your API
* __Low-Code-Platform__  
  Fusio allows you to build API endpoints without coding knowledge. I.e. it provides an Entity generator which you can
  use to easily create complete CRUD APIs.
* __API-Framework__  
  For more complex use cases you can use Fusio also as framework to build complete APIs from scratch. This means you
  build custom actions where you can use the wide PHP ecosystem to solve your task.

## Features

Fusio is an API management platform where you can configure operations which execute specific actions. An action
triggers your business logic. Fusio covers many aspects of the API life cycle so that you can concentrate on writing
the actual business logic of your API. Please take a look at our [documentation website](https://docs.fusio-project.org/)
for more information. The following feature list gives you a first overview:

* __OpenAPI generation__  
  Fusio generates automatically an OpenAPI specification for the defined routes
* __SDK generation__  
  Fusio can automatically generate a client SDK for your API based on the defined schema
* __Subscription support__  
  Fusio contains a event subscription layer which helps to build pub/sub for your API
* __Rate limiting__  
  Fusio provides a way to rate limit requests based on the user or app
* __Authorization__  
  Fusio uses OAuth2 for API authorization
* __Monetization__  
  Fusio provides a simple payment system to charge for specific routes
* __Validation__  
  Fusio uses the TypeSchema to automatically validate incoming request data
* __Analytics__  
  Fusio monitors all API activities and shows them on a dashboard
* __User management__  
  Fusio provides a developer app where new users can login or register a new account through GitHub, Google, Facebook or
  through normal email registration

# Apps

Since it is difficult to work with an API only app Fusio provides apps which help to work with the API. Mostly apps are
simple JS apps, which work with the internal API of Fusio. You can see a list of all available apps at our
[marketplace](https://www.fusio-project.org/marketplace). You can install such an app either through a CLI command i.e.
`php bin/fusio marketplace:install fusio` or through the backend app.

All apps are installed to the `apps/` folder. You need to tell Fusio the public url to the apps folder at the `.env`
file by defining the `APP_APPS_URL` variable. Depending on your setup this can be either a custom sub-domain like
`https://apps.acme.com` or simply the sub folder `https://acme.com/apps`.

## Backend

![Backend](https://www.fusio-project.org/media/backend/dashboard.png)

The backend app is the main app to configure and manage your API. The installer automatically installs this app. The app
is located at `/apps/fusio/`.

## VSCode

Fusio provides a [VSCode extension](https://marketplace.visualstudio.com/items?itemName=Fusio.fusio)
which can be used to simplify action development. This means you can develop every action directly inside
the VSCode editor.

# Services

Besides our core open-source product we provide different services to augment the functionality of Fusio. If you are
interested or you want to support our project please take a look at the following services:

* [Cloud](https://fusio.cloud/)  
  The cloud service allows you to create a Fusio instance in the cloud. It really simplifies the usage of Fusio since
  you can create and manage an instance through a simple web interface.
* [APIgen](https://apigen.app/)  
  APIgen is a code generator which allows you to generate code based on a data structure, which you can define
  at the backend. The tool generates based on this data structure a complete API tailored to your use case which
  you can use to CRUD those entities.
* [SDKgen](https://sdkgen.app/)  
  SDKgen provides an advanced client SDK generator to increase the adoption of your API by providing SDK support for
  multiple popular programming languages.

# Installation

It is possible to install Fusio either through composer or manually file download.

## Composer

```
composer create-project fusio/fusio
```

## Download

https://github.com/apioo/fusio/releases

## Configuration

You can either manually install Fusio with the steps below or you can also use the browser based installer at
`public/install.php`. Note because of security reasons it is highly recommended removing the installer script after the
installation.

* __Adjust the configuration file__  
  Open the file `.env` in the Fusio directory and change the `APP_URL` to the domain pointing to the public folder.
  Also insert the database credentials to the `APP_CONNECTION` keys. Optional adjust `APP_APPS_URL` to the public url
  of the apps folder (in case you want to use apps).
* __Execute the installation command__  
  The installation script inserts the Fusio database schema into the provided database. It can be executed with the
  following command `php bin/fusio migrate`.
* __Create administrator user__  
  After the installation is complete you have to create a new administrator account. Therefor you can use the following
  command `php bin/fusio adduser`. Choose as account type "Administrator".
* __Install backend app__  
  To manage your API through an admin panel you need to install the backend app. The app can be installed with the
  following command `php bin/fusio marketplace:install fusio`

You can verify the installation by visiting the `APP_URL` with a browser. You should see an API response that the
installation was successful.

In case you want to install Fusio on a specific database you need to adjust the `APP_CONNECTION` parameter. You can
use the following connection strings:

* MySQL: `pdo-mysql://root:test1234@localhost/fusio`
* PostgreSQL: `pdo-pgsql://postgres:postgres@localhost/fusio`
* SQLite: `pdo-sqlite:///fusio.sqlite`

In general it is possible to install Fusio on all database which are [supported](https://www.doctrine-project.org/projects/doctrine-dbal/en/current/reference/configuration.html#driver)
by our database abstraction layer but our internal test cases are only covering MySQL, PostgreSQL and SQLite so there is
no guarantee that everything works.

## Docker

It is possible to setup Fusio through docker. This has the advantage that you automatically get a complete running Fusio
system without configuration. This is especially great for testing and evaluation. To setup the container you have to
checkout the [repository](https://github.com/apioo/fusio-docker) and run the following command:

```
docker-compose up -d
```

This builds the Fusio system with a predefined backend account. The credentials are taken from the env variables
`FUSIO_BACKEND_USER`, `FUSIO_BACKEND_EMAIL` and `FUSIO_BACKEND_PW` in the `docker-compose.yml`. If you are planing to
run the container on the internet you must change these credentials.

# Documentation

Please check out our official documentation website where we bundle all documentation resources:
https://docs.fusio-project.org/

# Ecosystem overview

This should give you a first overview about all important repositories which belong to the Fusio project:

* [Fusio](https://github.com/apioo/fusio)  
  Contains the main Fusio repository where we collect and discuss all ideas and issues
* [Fusio-Impl](https://github.com/apioo/fusio-impl)  
  Contains the backend API implementation of Fusio. This is the place if you like to change the internal API of Fusio
* [Fusio-CLI](https://github.com/apioo/fusio-cli)  
  Contains the CLI client for Fusio, it is automatically included in every Fusio installation but you can also run the
  CLI client standalone. It allows you to directly interact with the API and to deploy specific YAML configuration files
* [Fusio-Model](https://github.com/apioo/fusio-model)  
  Contains all Fusio models automatically generated via [TypeSchema](https://typeschema.org/). This repository helps if
  you want to work with the Fusio API since you can use the same model classes which we also use at the backend
* [Fusio-Engine](https://github.com/apioo/fusio-engine)  
  Contains mostly interfaces which are also needed by adapters.
* [Fusio-Adapter](https://www.fusio-project.org/adapter)  
  Page which shows all available adapters. An adapter can extend Fusio by providing i.e. custom Actions or Connections
  to different services. I.e. we have an adapter [MongoDB](http://github.com/apioo/fusio-adapter-mongodb) which helps to
  work with a [MongoDB](https://www.mongodb.com/)
* [Fusio-Docker](https://github.com/apioo/fusio-docker)  
  Contains a Docker-Image to run Fusio, it helps to quickly create a Fusio instance in the cloud. You can find it also
  directly on [DockerHub](https://hub.docker.com/r/fusio/fusio)
* [Fusio-Docs](https://github.com/apioo/fusio-docs)  
  Contains our complete documentation [website](https://docs.fusio-project.org/). This is the place if you like to
  adjust or improve our documentation
* [App-Backend](https://github.com/apioo/fusio-apps-backend)  
  Contains the Fusio backend app which you can use to configure your API. This is the place if you like to change or
  improve the backend app
* [App-Developer](https://github.com/apioo/fusio-apps-developer)  
  Contains a developer portal app where external developers can register to use your API

# Contribution

Contributions to the project are always appreciated. There are many options available to improve the project (which is
not limited to coding). The following list shows some ways how you can participate:

## Developing

If you are a PHP or Javascript developer you can help to improve the system. If you want to create a new feature it is
in general recommended to create a new issue where we can talk about the feature. There are three main components of
Fusio:

**[Backend-API](https://github.com/apioo/fusio-impl)**

The backend API is the core of the system developed in PHP, which provides the basic functionality of Fusio. This is the
place to develop new core features and improvements.

**[Adapter](https://www.fusio-project.org/adapter)**

An adapter is a plugin to the Fusio system which can be used to connect to other remote services. I.e. you could create
a new adapter which speaks to a specific API or other remote services. This is easy to develop since you can build it in
a separate repository. Please use the keyword `fusio-adapter` in your `composer.json` file so that adapter gets listed
automatically on our website.

**[Backend-App](https://github.com/apioo/fusio-backend)**

This is the Angular app which is used as UI to control the backend. It is the main app to improve the Fusio backend.
But you are also free to develop new apps for special use cases which talk to the internal API of Fusio.

## Testing

We have a high PHPUnit test case coverage. Besides this it is always great if users checkout the current master version
of the project and try to test every aspect of the system. In case you have found an issue please report it through the
issue tracker.

## Documentation

We want to create a system which is easy to use also by novice users. To enable everybody to start using Fusio we need
a great documentation. We have bundled all documentation at our [documentation website](https://docs.fusio-project.org/)
please feel free to adjust or contribute new documents in case you think something is missing or wrong.

# Support

## Promotion

If you are a blogger or magazine we would be happy if you like to cover Fusio. Please take a look at the Media section
of our [About Page](https://www.fusio-project.org/about) to download the official icon set. In case you have any
questions please write us a message directly so we can help you to create great content.

## Consulting

If you are a company or freelancer and want to get detailed information how you can use Fusio you can contact us for
consulting. In the workshop we try to find the best way how you can use/integrate Fusio also we try to explain the
functionality and answer your questions.

## Donations

If this project helps you to generate revenue or in general if you like to support the project please check out the
donation options at our repository.
