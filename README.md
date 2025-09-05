
<p align="center">
    <a href="https://www.fusio-project.org/" target="_blank"><img src="https://www.fusio-project.org/img/fusio_64px.png"></a>
</p>

# Fusio

Self-Hosted API Management for Builders.

## 🚀 Use Cases

* __Database API Gateway__
  > Unlock legacy databases and expose them via modern REST APIs.

* __Custom Backend Logic for APIs__
  > Build and manage custom business logic tailored to your domain.

* __Gateway for Microservices__
  > Route, orchestrate, and secure traffic between internal services.

* __API Developer Portal__
  > Provide docs, testing, and SDKs for internal or external developers.

* __API Monetization__
  > Enable freemium or tiered access with quotas, limits, and billing hooks.

* __MCP Integration__
  > Leverage the Model Context Protocol to enable AI-driven access and control of API endpoints.

* __API Usage Analytics__
  > Monitor traffic, detect issues early, and understand API consumption.

* __Headless CMS Backend__
  > Manage and expose structured content to any frontend via APIs.

* __SDK Automation__
  > Automatically generate ready-to-use client SDKs (PHP, TypeScript, Python, etc.).

## 📦 Quick Start

### 🛠️ Installation

* __Download artifact__
  > You can either download the official [release](https://github.com/apioo/fusio/releases) or clone the repository.
  ```bash
  git clone https://github.com/apioo/fusio.git
  ```

* __Set up your `.env`__
  > Configure fitting database credentials at the `APP_CONNECTION` variable, all other parameters are optional.
  > * MySQL: `pdo-mysql://root:test1234@localhost/fusio`
  > * PostgreSQL: `pdo-pgsql://postgres:postgres@localhost/fusio`
  > * SQLite: `pdo-sqlite:///fusio.sqlite`

* __Run migrations__
  ```bash
  php bin/fusio migrate
  ```

* __Create administrator user__
  > After the installation is complete, you have to create a new administrator account. Choose as account type "Administrator".
  ```bash
  php bin/fusio adduser
  ```

* __Install backend app__
  ```bash
  php bin/fusio marketplace:install fusio
  ```

* __Start via PHP built-in server__
  > This should be only used for testing, for production you need a classical Nginx/Apache setup or use Docker, take a look at our [installation documentation](https://docs.fusio-project.org/docs/installation/) for more details.
  ```bash
  php -S 127.0.0.1:8080 -t public
  ```

### 🌐 Web-Installer

Instead of manual installation you can also use the web installer script located at `/install.php`
to complete the installation. After installation, it is recommended to delete this "install" script.

### 🐳 Docker

To run Fusio with Docker you only need the official Fusio [docker image](https://hub.docker.com/r/fusio/fusio)
and a database. The following example shows a minimal `docker-compose.yaml` which you can use to run Fusio.

```yaml
services:
  fusio:
    image: fusio/fusio
    restart: always
    environment:
      FUSIO_PROJECT_KEY: "42eec18ffdbffc9fda6110dcc705d6ce"
      FUSIO_CONNECTION: "pdo-mysql://fusio:61ad6c605975@mysql-fusio/fusio"
      FUSIO_BACKEND_USER: "test"
      FUSIO_BACKEND_EMAIL: "demo@fusio-project.org"
      FUSIO_BACKEND_PW: "test1234"
    links:
      - mysql-fusio
    ports:
      - "8080:80"

  mysql-fusio:
    image: mysql:8.0
    restart: always
    environment:
      MYSQL_RANDOM_ROOT_PASSWORD: "1"
      MYSQL_USER: "fusio"
      MYSQL_PASSWORD: "61ad6c605975"
      MYSQL_DATABASE: "fusio"
    volumes:
      - ./db:/var/lib/mysql
```

## 🧩 Apps

Fusio includes a flexible app system that lets you install various web-based apps to support
different API-related use cases. These apps are typically simple JavaScript frontends that
interact with Fusio's internal API.

You can browse all available apps in the [Fusio Marketplace](https://www.fusio-project.org/marketplace),
and install them using either the CLI:

```bash
php bin/fusio marketplace:install fusio
```

or directly through the backend interface.

### 🖥️ Backend

![Backend](https://www.fusio-project.org/media/backend/dashboard.png)

The backend app is the main app to configure and manage your API located at `/apps/fusio/`.

### 💡 VSCode

Fusio provides a [VSCode extension](https://marketplace.visualstudio.com/items?itemName=Fusio.fusio)
which can be used to simplify action development.

## 🔗 Integration

### 🧰 SDK

To build and integrate applications with Fusio, you can use one of our officially supported SDKs, which simplify interaction with a Fusio instance. Alternatively, you can directly communicate with the REST API for full control and flexibility.

| Language   | GitHub                                                  | Package                                                           | Example                                                      |
|------------|---------------------------------------------------------|-------------------------------------------------------------------|--------------------------------------------------------------|
| C#         | [GitHub](https://github.com/apioo/fusio-sdk-csharp)     | [NuGet](https://www.nuget.org/packages/Fusio.SDK)                 | [Example](https://github.com/apioo/fusio-sample-csharp-cli)  |
| Go         | [GitHub](https://github.com/apioo/fusio-sdk-go)         |                                                                   | [Example](https://github.com/apioo/fusio-sample-go-cli)      |
| Java       | [GitHub](https://github.com/apioo/fusio-sdk-java)       | [Maven](https://mvnrepository.com/artifact/org.fusio-project/sdk) | [Example](https://github.com/apioo/fusio-sample-java-cli)    |
| Javascript | [GitHub](https://github.com/apioo/fusio-sdk-javascript) | [NPM](https://www.npmjs.com/package/fusio-sdk)                    |                                                              |
| PHP        | [GitHub](https://github.com/apioo/fusio-sdk-php)        | [Packagist](https://packagist.org/packages/fusio/sdk)             | [Example](https://github.com/apioo/fusio-sample-php-cli)     |
| Python     | [GitHub](https://github.com/apioo/fusio-sdk-python)     | [PyPI](https://pypi.org/project/fusio-sdk/)                       | [Example](https://github.com/apioo/fusio-sample-python-cli)  |

### 🖥️ Frontend

| Framework | GitHub                                                           | Package                                             | Example |
|-----------|------------------------------------------------------------------|-----------------------------------------------------|---------|
| Angular   | [GitHub](https://github.com/apioo/fusio-sdk-javascript-angular)  | [NPM](https://www.npmjs.com/package/ngx-fusio-sdk)  | [Example](https://github.com/apioo/fusio-sample-javascript-angular)        |

### 📡 REST API

| Domain   | Documentation                                       | Specification                                                                           |
|----------|-----------------------------------------------------|-----------------------------------------------------------------------------------------|
| Backend  | [ReDoc](https://www.fusio-project.org/api/backend)  | [OpenAPI](https://demo.fusio-project.org/system/generator/spec-openapi?filter=backend)  |
| Consumer | [ReDoc](https://www.fusio-project.org/api/consumer) | [OpenAPI](https://demo.fusio-project.org/system/generator/spec-openapi?filter=consumer) |
| System   | [ReDoc](https://www.fusio-project.org/api/system)   | [OpenAPI](https://demo.fusio-project.org/system/generator/spec-openapi?filter=system)   |

## 🌍 Ecosystem

Besides our core product, we offer additional services to augment the functionality of Fusio.

* [SDKgen](https://sdkgen.app/)  
  SDKgen is a powerful code generator to automatically build client SDKs for your REST API.
* [APIgen](https://apigen.app/)  
  Generate fully working and customizable APIs based on your data model.
* [APImon](https://apimon.app/)  
  APImon provides an intuitive service to monitor and analyze API endpoints.
* [TypeSchema](https://typeschema.org/)  
  A JSON format to describe data models in a language neutral format.
* [TypeAPI](https://typeapi.org/)  
  An OpenAPI alternative to describe REST APIs for type-safe code generation.
* [TypeHub](https://typehub.cloud/)  
  A collaborative platform to design and build API models and client SDKs.
* [PSX](https://phpsx.org/)  
  An innovative PHP framework dedicated to build fully typed REST APIs.

## 🏷️ Domains

By default, the entire Fusio project can be hosted on a single domain. In this setup:

* Your API is served from the root path (e.g., https://acme.com/).
* Web apps like the developer portal and admin backend are accessible under the `/apps` directory (e.g., https://acme.com/apps/developer).

This setup is quick to get started with and requires no additional configuration.
For production environments, we recommend a subdomain-based structure:

* __api.acme.com__  
  Hosts only the Fusio API. In this setup, you can safely remove the `apps/` folder from the `public/` directory.
* __developer.acme.com__  
  Hosts the **Developer App**, a portal where third-party developers can register, view documentation, and access their credentials. 
* __fusio.acme.com__ (optional)  
  Hosts the **Backend App**, used to manage your Fusio instance. You can also host this on a separate internal domain.

> Note: This is just a suggested setup. You're free to choose any domain or subdomain structure that best fits your infrastructure.

## 📚 Documentation

Please check out our official documentation website where we bundle all documentation resources:

https://docs.fusio-project.org/

## 🤝 Support

### 💬 Get Help

If you have questions or run into issues while using Fusio:

- Open a [discussion](https://github.com/apioo/fusio/discussions) for general questions, feedback, or feature ideas.
- Report bugs or technical problems via the [issue tracker](https://github.com/apioo/fusio/issues).
- Join our [Discord community](https://discord.gg/eMrMgwsc6e) to chat directly with the developers and other users.

If you're a company or freelancer looking for more tailored help, please check out our **consulting** services below.

---

### 📣 Promotion & Media

Are you a blogger, writer, or run a developer-focused publication? We'd love for you to cover Fusio!

Visit the [Media Page](https://www.fusio-project.org/media) to download official icons for use in your articles or videos.

---

### 🧑‍🏫 Consulting & Workshops

For companies or freelancers who want in-depth guidance on using and integrating Fusio:

- We offer **consulting services** to help you evaluate whether Fusio fits your architecture.
- Our **workshops** walk you through key functionality, answer your specific questions, and help identify the best integration approach.

Feel free to [contact us](https://www.fusio-project.org/contact) for more details.

---

### 💖 Support Fusio

If Fusio helps you build APIs faster or adds value to your projects, please consider supporting our work:

- ⭐ Star the project on GitHub
- ☕ [Sponsor via GitHub](https://github.com/sponsors/chriskapp)
- 💬 Spread the word on social media or write about Fusio

Every bit of support helps us continue improving the platform!

---

### 🤝 Project Partners

We’re grateful to our partners who support the Fusio project and share our vision of advancing open API development.

If your company is interested in becoming a partner and being listed here, consider [becoming a sponsor](https://github.com/sponsors/chriskapp).

<a href="https://jb.gg/OpenSource">
 <picture>
   <source media="(prefers-color-scheme: dark)" srcset="https://www.jetbrains.com/company/brand/img/logo_jb_dos_3.svg">
   <source media="(prefers-color-scheme: light)" srcset="https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg">
   <img alt="JetBrains logo." src="https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg">
 </picture>
</a>
