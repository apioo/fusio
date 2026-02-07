
# Contributing to Fusio

Thanks for your interest in contributing to **Fusio**! 🎉  
We welcome all kinds of contributions, not only code, but also documentation, testing, bug reports, ideas, and
community support.

This guide helps you get started and shows you where your contribution fits best.

---

## 🧑‍💻 Development

If you are a **PHP** or **JavaScript** developer, you can directly contribute to Fusio's core or ecosystem.

Before starting work on larger features or changes, please **open an issue first** so we can discuss the idea and avoid
duplicated work.

Fusio consists of several main components:

### 🔧 Backend API (Core)

**Repository:** https://github.com/apioo/fusio-impl

The backend API is the core of Fusio and is written in PHP.  
This is the right place for:

- core features
- API behavior changes
- performance improvements
- security fixes

---

### 🔌 Adapters (Plugins)

**Overview:** https://www.fusio-project.org/adapter

Adapters extend Fusio with integrations to external services and APIs.

Examples:
- Database connectors
- Third-party APIs
- Custom action implementations

You can build adapters in a separate repository.  
Please add the keyword `fusio-adapter` to your `composer.json` so your adapter is automatically listed on the website.

---

### 🖥 Backend App (Admin UI)

**Repository:** https://github.com/apioo/fusio-apps-backend

The backend app is the Angular-based UI used to manage Fusio.  
This is the right place to improve:

- user experience
- dashboards
- forms & workflows
- admin tooling

You are also welcome to create **custom apps** for specific use cases that talk to the Fusio internal API.

---

## 🧪 Testing

Fusio has good PHPUnit test coverage, but manual testing is always valuable.

You can help by:
- testing new releases
- testing the `main` branch
- reporting regressions
- improving test coverage

If you find an issue, please report it here:  
👉 https://github.com/apioo/fusio/issues

---

## 📚 Documentation

Good documentation is essential to make Fusio accessible to new users.

The documentation lives here:  
👉 https://docs.fusio-project.org/  
👉 Repository: https://github.com/apioo/fusio-docs

The docs are built with **Docusaurus**, so contributions are straightforward:
- fix typos
- clarify confusing sections
- add missing examples
- improve onboarding guides

Documentation contributions are just as valuable as code!

---

## 💬 Ideas & Feedback

If you have:
- feature ideas
- UX feedback
- architecture suggestions

Please start a discussion or open an issue:

👉 https://github.com/apioo/fusio/discussions

This helps us align on direction before implementation starts.

---

## ❤️ Support & Sponsorship

If you'd like to financially support Fusio, check out the **Sponsor this project** panel on GitHub:

👉 https://github.com/apioo/fusio

Your support helps keep the project healthy and evolving.

---

# 📦 Repository Overview

Fusio is split into multiple repositories.  
Use this overview to find the right place for your contribution:

- **Fusio**  
  https://github.com/apioo/fusio  
  Main coordination repo (issues, discussions, meta project). This repository itself contains no core implementation code.

- **Fusio-Impl (Backend API)**  
  https://github.com/apioo/fusio-impl  
  Core PHP implementation of Fusio.

- **Fusio-CLI**  
  https://github.com/apioo/fusio-cli  
  CLI client for managing and deploying Fusio configurations.

- **Fusio-Model**  
  https://github.com/apioo/fusio-model  
  Shared model classes generated via https://typeschema.org/  

- **Fusio-Engine**  
  https://github.com/apioo/fusio-engine  
  Core interfaces and contracts used by adapters and extensions.

- **Adapters**  
  https://www.fusio-project.org/adapter  
  List of all available adapters.  

- **Fusio-Docker**  
  https://github.com/apioo/fusio-docker  
  Official Docker image and setup.

- **Workers**  
  https://www.fusio-project.org/worker  
  Workers allow executing action logic in other languages like JavaScript or Python.

- **Fusio Docs**  
  https://github.com/apioo/fusio-docs  
  Documentation website content.

- **Backend App (Admin UI)**  
  https://github.com/apioo/fusio-apps-backend  
  Angular-based admin UI.

- **Developer App (Developer Portal)**  
  https://github.com/apioo/fusio-apps-developer  
  Portal for API consumers.

- **Apps Marketplace**  
  https://www.fusio-project.org/marketplace  
  Overview of all available apps.

---

## 🏁 Getting Started as a Contributor

If you're new to Fusio, a great way to start is:

1. Run Fusio locally
2. Follow the Getting Started guide
3. Look for small issues to fix (docs, tests, UX)
4. Open a PR and ask for feedback

We're happy to help you get started, welcome to the project! 🚀

