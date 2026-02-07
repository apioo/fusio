
<p align="center">
    <a href="https://www.fusio-project.org/" target="_blank"><img src="https://www.fusio-project.org/img/fusio_64px.png"></a>
</p>

# Fusio

**Self-hosted API management platform to build, secure, and operate APIs.**

Fusio is an open source API management and backend platform that helps you create,
manage, and scale APIs in one place. It provides tools for routing, authentication,
custom business logic, SDK generation, and optional AI-assisted backend development.

Website: https://www.fusio-project.org  
Documentation: https://docs.fusio-project.org

## 🚀 Use Cases

Fusio can be used in a wide range of API management and backend development scenarios:

- **Custom API Logic** - Build custom backend logic with reusable actions
- **Microservice API Gateway** - Secure, route, and orchestrate traffic between microservices
- **API Developer Portal** - Provide API docs, testing tools, and SDK downloads
- **API Monetization** - Manage plans, quotas, rate limits, and access control
- **AI / MCP Integration** - Expose and control APIs for AI tools and agents
- **API Analytics & Monitoring** - Track API usage, performance, and errors
- **AI-Assisted API Development** - Generate custom backend logic using AI and natural language
- **SDK Automation** - Automatically generate client SDKs for your APIs
- **Database API Gateway** - Expose legacy databases as REST APIs

## 📦 Installation

### 🐳 Docker

The fastest way to try Fusio locally is with Docker and docker-compose.

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

> docker compose up -d

**After startup**

* Backend: http://localhost:8080/apps/fusio
* Login with the credentials you configured
* For the first steps, take a look at our [Getting Started](https://docs.fusio-project.org/docs/bootstrap) guide

### 🛠️ Manual Installation

* __Download artifact__

  You can either download the official [release](https://github.com/apioo/fusio/releases) or clone the repository.
  ```bash
  git clone https://github.com/apioo/fusio.git
  ```

* __Configure `.env`__

  Configure fitting database credentials at the `APP_CONNECTION` variable, all other parameters are optional.
  ```bash
  APP_CONNECTION=pdo-mysql://root:password@localhost/fusio
  APP_URL=http://localhost:8080
  ```

  > It is also recommended to provide the `APP_URL` which contains the domain pointing to the public folder i.e.
    `https://api.my_domain.com` or `https://my_domain.com/fusio`, this is required if you host Fusio inside
    a sub-folder otherwise Fusio tries to detect the domain via the Host header.

  Supported DBs:
  * MySQL: `pdo-mysql://user:pass@host/db`
  * PostgreSQL: `pdo-pgsql://user:pass@host/db`
  * SQLite: `pdo-sqlite:///fusio.sqlite`

* __Run migrations__
  ```bash
  php bin/fusio migrate
  ```

* __Create administrator user__
  ```bash
  php bin/fusio adduser
  ```

  > Choose Administrator as account type.

* __Install backend app__
  ```bash
  php bin/fusio marketplace:install fusio
  ```

* __Start server (dev only)__
  ```bash
  php -S 127.0.0.1:8080 -t public
  ```

  > This should be only used for testing, for production you need a classical Nginx/Apache setup or use Docker, take a look at our [installation documentation](https://docs.fusio-project.org/docs/installation/) for more details.

### 🌐 Web-Installer

Instead of manual installation you can also use the web installer script located at `/install.php`
to complete the installation. After installation, it is recommended to delete this "install" script.

## 🧭 Getting Started

Use our [Getting Started](https://docs.fusio-project.org/docs/bootstrap) guide to build your first action and configure
an operation to expose the action as API endpoint.

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

* [Marketplace](https://www.fusio-project.org/marketplace)  
  The Fusio marketplace is the place to share apps and actions with other Fusio users, it helps to quickly build
  your API by using existing code from other users. You can register and configure the credentials at your local
  Fusio installation under System / Config s. `marketplace_client_id` and `marketplace_client_secret` then you can use
  the panel under Development / Marketplace to install apps or actions.
* [SDKgen](https://sdkgen.app/)  
  SDK as a service platform which helps you to generate client SDKs for your API in different languages like `CSharp`,
  `Go`, `Java` and `Python` which helps your customers to interact with your API. Therefor you need to register at the
  SDKgen app and provide the credentials under System / Config s. `sdkgen_client_id` and `sdkgen_client_secret`.
  Then you can generate the SDK directly at the backend under Development / SDK. 
* [TypeHub](https://typehub.cloud/)  
  API and data design platform, basically you can push your API specification to this platform so that users can simply
  discover your API. It tracks all changes of your API so that you have always a clean history how your API evolves.
* [APIgen](https://apigen.app/)  
  Service which generates fully working Fusio APIs based on a data model. It also includes a simple Angular
  frontend app to CRUD your models. It can be seen as low-code generator to quickly generate CRUD APIs but the
  generated code is clean and can be also used as foundation for your next app.
* [APImon](https://apimon.app/)  
  Simple API monitoring service which helps to monitor your Fusio installation. It is optimized for Fusio, but it can be
  also used for different API endpoints. APImon invokes your endpoints in specific intervals and notifies you about
  changes. It also includes an uptime page for your users for example s. https://api.apimon.app/status/fusio_marketplace

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
