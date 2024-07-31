
# Contribution

Contributions to the project are always appreciated. There are many options available to improve the project (which is
not limited to coding). The following list shows some ways how you can participate:

## Developing

If you are a PHP or Javascript developer you can help to improve the system. If you want to create a new feature it is
in general recommended to create for larger topics a new issue where we can talk about the feature. There are three main
components of Fusio:

**[Backend-API](https://github.com/apioo/fusio-impl)**

The backend API is the core of the system developed in PHP, which provides the basic functionality of Fusio. This is the
place to develop new core features and improvements.

**[Adapter](https://www.fusio-project.org/adapter)**

An adapter is a plugin to the Fusio system which can be used to connect to other remote services. I.e. you could create
a new adapter which speaks to a specific API or other remote services. This is easy to develop since you can build it in
a separate repository. Please use the keyword `fusio-adapter` in your `composer.json` file so that adapter gets listed
automatically on our website.

**[Backend-App](https://github.com/apioo/fusio-apps-backend)**

This is the Angular app which is used as UI to control the backend. It is the main app to improve the Fusio backend.
But you are also free to develop new apps for special use cases which talk to the internal API of Fusio.

## Testing

We have a high PHPUnit test case coverage. Besides this it is always great if users checkout the current master version
of the project and try to test every aspect of the system. In case you have found an issue please report it on
[GitHub](https://github.com/apioo/fusio/issues).

## Documentation

We want to create a system which is easy to use also by novice users. To enable everybody to start using Fusio we need
a great documentation. We have bundled all documentation at our [documentation website](https://docs.fusio-project.org/).
It is based on [Docusaurus](https://docusaurus.io/) so it should be easy to adjust or contribute new documents in case
you think something is missing or wrong.

## Donations

If your want to financially support our project you can take a look at the [Fusio repository](https://github.com/apioo/fusio)
there is a "Sponsor this project" panel where you can find all donation options.

# Repository overview

The Fusio project has several components which together build the Fusio system. The following list provides an overview
about all relevant repositories so that you can easily find the fitting repository for your contribution.

* [Fusio](https://github.com/apioo/fusio)  
  Contains the main Fusio repository where we collect and discuss all ideas and issues. The repository does not contain
  any Fusio related code, it only requires the `fusio/impl` package.
* [Fusio-Impl](https://github.com/apioo/fusio-impl)  
  Contains the backend API implementation of Fusio. This is the place if you like to change the internal API of Fusio.
* [Fusio-CLI](https://github.com/apioo/fusio-cli)  
  Contains the CLI client of Fusio, it is automatically included in every Fusio installation but you can also run the
  CLI client standalone. It allows you to directly interact with the API and to deploy specific YAML configuration files.
* [Fusio-Model](https://github.com/apioo/fusio-model)  
  Contains all Fusio models automatically generated via [TypeSchema](https://typeschema.org/). This repository helps if
  you want to work with the Fusio API since you can use the same model classes which we also use at the backend.
* [Fusio-Engine](https://github.com/apioo/fusio-engine)  
  Contains mostly interfaces which are also needed by adapters.
* [Fusio-Adapter](https://www.fusio-project.org/adapter)  
  Page which shows all available adapters. An adapter can extend Fusio by providing i.e. custom Actions or Connections
  to different services. I.e. we have an adapter [MongoDB](http://github.com/apioo/fusio-adapter-mongodb) which helps to
  work with a [MongoDB](https://www.mongodb.com/).
* [Fusio-Docker](https://github.com/apioo/fusio-docker)  
  Contains a Docker-Image to run Fusio, it helps to quickly create a Fusio instance in the cloud. You can find it also
  directly on [DockerHub](https://hub.docker.com/r/fusio/fusio).
* [Fusio-Worker](https://www.fusio-project.org/worker)  
  This overview shows all available worker for Fusio. A worker provides a way to execute action logic in a different
  programming language i.e. JavaScript or Python.
* [Fusio-Docs](https://github.com/apioo/fusio-docs)  
  Contains our complete documentation [website](https://docs.fusio-project.org/). This is the place if you like to
  adjust or improve our documentation.
* [App-Backend](https://github.com/apioo/fusio-apps-backend)  
  Contains the Fusio backend app which you can use to configure your API. This is the place if you like to change or
  improve the backend app.
* [App-Developer](https://github.com/apioo/fusio-apps-developer)  
  Contains the developer portal app where external developers can register to use your API.
* [Apps](https://www.fusio-project.org/marketplace)
  The Backend and Developer app are the main apps regarding Fusio but we have also several other small apps. You can
  get an overview at our marketplace.

