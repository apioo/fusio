
<p align="center">
    <a href="https://www.fusio-project.org/" target="_blank">
        <img src="https://www.fusio-project.org/img/fusio_64px.png" width="64">
    </a>
</p>

<h1 align="center">Fusio</h1>

<p align="center">
  <strong>Open Source API Management Platform</strong><br />
  Turn your business logic into scalable API products for humans and AI agents.
</p>

---

Fusio is a self-hosted backend platform and API gateway that bridges the gap between your internal infrastructure and
the outside world. Whether you are exposing legacy databases, orchestrating microservices, or building custom business
logic, Fusio handles the heavy lifting. It transforms your services into a professional API product, complete with an
automated developer portal, SDK generation, and monetization tools. With native MCP support, Fusio also acts as a bridge
to the AI ecosystem, allowing you to turn your backend logic into tools for autonomous agents.

## 🚀 Features

* **Database API Gateway** - Instantly expose legacy SQL/NoSQL databases (MySQL, PostgreSQL, etc.) as REST APIs.
* **Microservice Gateway** - Securely route, orchestrate, and load-balance traffic between your distributed services.
* **Custom API Logic** - Build powerful backend logic using reusable actions in PHP or Javascript.
* **Agent Development** - Use your custom API logic as "Tools" to build and power autonomous AI agents.
* **AI-Assisted Development** - Generate custom backend logic and schemas using natural language prompts.
* **MCP Integration** - Native support for the Model Context Protocol to expose APIs to AI ecosystems.
* **API Developer Portal** - A self-service portal for third-party developers with docs, testing tools, and keys.
* **SDK Automation** - Zero-effort generation of client SDKs for all major programming languages.
* **API Monetization** - Turn your API into a product with subscription plans, quotas, and automated billing.
* **Analytics & Monitoring** - Real-time tracking of API usage, performance metrics, and error logging.

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

### Action

At the core of Fusio, you describe your business logic in an Action. An Action is a PHP class that receives an incoming
request and returns a response.

```php
<?php

namespace App\Action;

use Fusio\Engine;

class MyAction implements Engine\ActionInterface
{
    public function handle(Engine\RequestInterface $request, Engine\ParametersInterface $configuration, Engine\ContextInterface $context): mixed
    {
        return [
            'hello' => 'world'
        ];
    }
}
```

Once defined, this action can be bound to an Operation so that it executes for a specific HTTP method and path.

#### Configuration

Fusio allows you to build actions that are configurable directly from the backend. In the example below, we add a
`message` configuration parameter so a user can customize the response without changing the code.

```php
<?php

namespace App\Action;

use Fusio\Engine;

class MyAction extends Engine\ActionAbstract
{
    public function handle(Engine\RequestInterface $request, Engine\ParametersInterface $configuration, Engine\ContextInterface $context): mixed
    {
        return [
            'hello' => $configuration->get('message'),
        ];
    }

    public function configure(Engine\Form\BuilderInterface $builder, Engine\Form\ElementFactoryInterface $elementFactory): void
    {
        $builder->add($elementFactory->newInput('message', 'Message', 'text', 'The message which should be returned'));
    }
}
```

In the backend, this parameter is now exposed via a generated form:

![Action_Configuration](https://www.fusio-project.org/media/backend/action_configuration.png)

This concept makes actions highly reusable for both developers and non-technical users. We also provide a
[preset of actions](https://docs.fusio-project.org/docs/backend/api/action/) for common tasks. If you build an action
you'd like to share, check out our [Adapter](https://www.fusio-project.org/adapter) page. See our
[custom action](https://docs.fusio-project.org/docs/development/custom_action) documentation for more details.

#### Worker

Fusio provides Worker actions, which allow you to develop logic directly in the backend without building custom PHP
classes.

![Worker_PHP_Designer](https://www.fusio-project.org/media/backend/worker_php_designer.png)

This also enables you to build actions in other languages like JavaScript or Python, which is ideal if your team is more
familiar with those ecosystems. Learn more in the [worker documentation](https://docs.fusio-project.org/docs/action/worker).

#### AI

With the 7.0 release, we introduced a new Agent concept that allows you to generate worker actions using AI.

![Agent_Message](https://www.fusio-project.org/media/backend/agent_message.png)

This makes developing backend logic accessible to everyone. Simply describe the logic you need:

> return a list of popular composers of the 19th century

The Agent then generates the appropriate action logic automatically. Fusio supports multiple providers, including
Ollama, ChatGPT, and Gemini, so you are never locked into a specific AI. Explore the AI
[agent documentation](https://docs.fusio-project.org/docs/ai/) for more.

## 🧩 Apps

Fusio includes a flexible app system that lets you install various web-based apps to support different API-related use
cases. These apps are typically simple JavaScript frontends that interact with Fusio's internal API.

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

To build and integrate applications with Fusio, you can use one of our officially supported SDKs, which simplify
interaction with a Fusio instance. Alternatively, you can directly communicate with the REST API for full control and
flexibility.

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
  The Fusio marketplace is the place to share apps and actions with other Fusio users, it helps to quickly build your
  API by using existing code from other users. You can register and configure the credentials at your local Fusio
  installation under System / Config s. `marketplace_client_id` and `marketplace_client_secret` then you can use the
  panel under Development / Marketplace to install apps or actions.
* [SDKgen](https://sdkgen.app/)  
  SDK as a service platform which helps you to generate client SDKs for your API in different languages like `CSharp`,
  `Go`, `Java` and `Python` which helps your customers to interact with your API. Therefor you need to register at the
  SDKgen app and provide the credentials under System / Config s. `sdkgen_client_id` and `sdkgen_client_secret`.
  Then you can generate the SDK directly at the backend under Development / SDK. 
* [TypeHub](https://typehub.cloud/)  
  API and data design platform, basically you can push your API specification to this platform so that users can simply
  discover your API. It tracks all changes of your API so that you have always a clean history how your API evolves.
* [APIgen](https://apigen.app/)  
  Service which generates fully working Fusio APIs based on a data model. It also includes a simple Angular frontend app
  to CRUD your models. It can be seen as low-code generator to quickly generate CRUD APIs but the generated code is
  clean and can be also used as foundation for your next app.
* [APImon](https://apimon.app/)  
  Simple API monitoring service which helps to monitor your Fusio installation. It is optimized for Fusio, but it can be
  also used for different API endpoints. APImon invokes your endpoints in specific intervals and notifies you about
  changes. It also includes an uptime page for your users for example s. https://api.apimon.app/status/fusio_marketplace

## 🏷️ Domains

By default, the entire Fusio project can be hosted on a single domain. In this setup:

* Your API is served from the root path (e.g., https://acme.com/).
* Web apps like the developer portal and admin backend are accessible under the `/apps` directory (e.g., https://acme.com/apps/developer).

This setup is quick to get started with and requires no additional configuration. For production environments, we
recommend a subdomain-based structure:

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

## 🤝 Support & Community

If you have questions, found a bug, or want to share your feedback, we are active across the following channels:

* **[GitHub Discussions](https://github.com/apioo/fusio/discussions)** - Best for "How-to" questions, architectural feedback, and sharing feature ideas.
* **[Discord Community](https://discord.gg/eMrMgwsc6e)** - Join our real-time chat to connect with other Fusio developers and the core maintainers.
* **[Issue Tracker](https://github.com/apioo/fusio/issues)** - Report technical bugs or reproducible errors. Please check the **[Documentation](https://docs.fusio-project.org/)** first.
* **[Direct Contact](https://www.fusio-project.org/contact)** - Reach out via our official website for specific inquiries or high-level feedback.

## 🤝 Partners

We're grateful to our partners who support the Fusio project and share our vision of advancing open API development.

If your company is interested in becoming a partner and being listed here, consider [becoming a sponsor](https://github.com/sponsors/chriskapp).

<a href="https://jb.gg/OpenSource">
 <picture>
   <source media="(prefers-color-scheme: dark)" srcset="https://www.jetbrains.com/company/brand/img/logo_jb_dos_3.svg">
   <source media="(prefers-color-scheme: light)" srcset="https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg">
   <img alt="JetBrains logo." src="https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg">
 </picture>
</a>
