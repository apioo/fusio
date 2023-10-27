
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

# Ecosystem

Fusio is an open source project which you can use freely for private and commercial projects under the terms of the
Apache 2.0 license. Besides our core product we offer additional services to augment the functionality of Fusio.

* [TypeAPI](https://typeapi.org/)  
  An OpenAPI alternative to describe REST APIs for type-safe code generation.
* [TypeSchema](https://typeschema.org/)  
  A JSON format to describe data models in a language neutral format.
* [TypeHub](https://typehub.cloud/)  
  A collaborative platform to design and build API models and client SDKs.
* [SDKgen](https://sdkgen.app/)  
  SDKgen is a powerful code generator to automatically build client SDKs for your REST API.
* [APIgen](https://apigen.app/)  
  Generate fully working and customizable APIs based on your data model.
* [PSX](https://phpsx.org/)  
  An innovative PHP framework dedicated to build fully typed REST APIs.

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

## Domains

By default the complete Fusio project can be hosted on a single domain. In this setup your API is served at the root
directory and the developer portal and backend apps are directly served from the /apps folder. This setup is easy to use
since it requires no configuration. If you want to run Fusio in a production environment we recommend to create the
following sub-domain structure:

* __api.acme.com__  
  Contains only Fusio where your API is served, in this case you can delete the apps/ folder from the public/ folder
* __developer.acme.com__  
  Contains the developer portal app where external developers can register 
* __fusio.acme.com__  
  Optional the backend app where you can manage your Fusio instance. You can host this also on a complete separate
  internal domain, the backend app only needs access to the Fusio API.

This is of course only a suggestion and you are free to choose the domain names how you like.

# Documentation

Please check out our official documentation website where we bundle all documentation resources:
https://docs.fusio-project.org/

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

## Supporter

Thanks to JetBrains for supporting our project.

<a href="https://jb.gg/OpenSourceSupport">
<img src="https://resources.jetbrains.com/storage/products/company/brand/logos/jb_beam.svg">
</a>
