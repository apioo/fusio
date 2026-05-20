# Contributing to Fusio

Thanks for your interest in contributing to **Fusio**! 🎉  
We welcome all kinds of contributions, not just code, but also documentation, testing, bug reports, feature ideas, and community support.

This guide is designed to help you get started quickly and find where your contributions can make the biggest impact.

---

## 🏁 Getting Started as a New Contributor

If you are new to Fusio, here is the fastest way to get your feet wet:

1. **Spin up Fusio locally:** Use the official [Docker setup](https://github.com/apioo/fusio-docker) to get an environment running in minutes.
2. **Explore the codebase:** Check out the [Getting Started guide](https://docs.fusio-project.org/) to understand how the system components interact.
3. **Find an issue:** Look for issues labeled `bug` or `evaluation` in our main repository tracking board.
4. **Join the conversation:** Open a discussion if you have questions before writing code.

---

## 🧑‍💻 Development

Fusio is built as a modular ecosystem. If you are a **PHP**, **TypeScript/JavaScript**, or **Angular** developer, you 
can directly contribute to our core or ecosystem projects.

> ⚠️ **Important:** For larger features, structural changes, or API behavior alterations, please **open an issue or discussion first**. This allows us to align on the architecture and avoids duplicated effort.

Our primary development is divided into three areas:

### 🔧 Backend API (Core)
* **Repository:** [apioo/fusio-impl](https://github.com/apioo/fusio-impl)
* **Stack:** PHP
* **What to contribute:** Core API behavior, performance optimizations, security patches, and internal engine refinements.

### 🔌 Adapters & Workers (Plugins)
* **Overview:** [Fusio Adapters](https://www.fusio-project.org/adapter) & [Fusio Workers](https://www.fusio-project.org/worker)
* **What to contribute:** Connectors to third-party services, new database drivers, or multi-language execution workers (JavaScript, Python, etc.).
* **Tip:** If you build a custom adapter in your own repository, tag it with the keyword `fusio-adapter` in your `composer.json` so it automatically syncs with our website directory.

### 🖥 User Interfaces (Admin & Developer Apps)
* **Repositories:** [Backend Admin UI](https://github.com/apioo/fusio-apps-backend) | [Developer Portal](https://github.com/apioo/fusio-apps-developer)
* **Stack:** Angular / TypeScript
* **What to contribute:** User experience (UX) enhancements, form building workflows, configuration dashboards, and client-side localization.

---

## 🛠 Contribution Guidelines & Quality Standards

To maintain code quality and keep the review process moving smoothly, please follow these guidelines when submitting code:

### Code Style
* **PHP:** Code must adhere to PSR-12 coding standards.
* **Frontend:** Maintain strict TypeScript typing and follow standard Angular architectural patterns.

### Testing
* We heavily rely on automated testing. If you add a feature or fix a bug in the core (`fusio-impl`), please include corresponding **PHPUnit** tests.
* Ensure all existing tests pass locally before pushing your branch.

### Pull Request Checklist
Before submitting a PR, make sure you have:
- [ ] Forked the correct repository and created a descriptive feature branch (e.g., `feat/issue-[id]-add-oauth-provider`).
- [ ] Run local tests to ensure no regressions are introduced.
- [ ] Kept your commits clean and provided descriptive commit messages.
- [ ] Updated the documentation if your change modifies configuration files, environments, or public behavior.

---

## 📚 Documentation & Testing

Contributions to our documentation and testing suites are just as valuable as core code!

### Improving Documentation
Our documentation is built using **Docusaurus**.
* **Repository:** [apioo/fusio-docs](https://github.com/apioo/fusio-docs)
* **Live Site:** [docs.fusio-project.org](https://docs.fusio-project.org/)
* **How to help:** Fix typos, add missing code examples, clarify onboarding flows, or write tutorials for common deployment scenarios.

### Manual & Regression Testing
You can help the community by testing the `main` branches or upcoming release candidates in your real-world environments.
If you spot an issue, regression, or edge-case bug, please document it clearly and report it here:

👉 [Report an Issue](https://github.com/apioo/fusio/issues)

---

## 💬 Ideas & Feedback

Got a great feature idea, an architectural suggestion, or feedback on usability? We want to hear it! Please start a
thread on our discussions board so the community can collaborate:

👉 [Join the GitHub Discussions](https://github.com/apioo/fusio/discussions)

---

# 📦 Repository Directory

Because Fusio is heavily decoupled, it spans multiple repositories. Use this quick reference index to find exactly where
your contribution belongs:

| Repository / Resource | Description |
| :--- | :--- |
| 🌐 [apioo/fusio](https://github.com/apioo/fusio) | **Main Meta Repo:** Issues, roadmap, and high-level project discussions. Contains no core code. |
| ⚙️ [apioo/fusio-impl](https://github.com/apioo/fusio-impl) | **Backend Core:** The primary engine and PHP implementation of the API. |
| 💻 [apioo/fusio-cli](https://github.com/apioo/fusio-cli) | **CLI Client:** Tooling for deploying, managing, and exporting configurations. |
| 🐳 [apioo/fusio-docker](https://github.com/apioo/fusio-docker) | **Containerization:** Official production and local development Docker setups. |
| 🗺️ [apioo/fusio-engine](https://github.com/apioo/fusio-engine) | **Core Contracts:** Abstract interfaces used by adapters and extensions. |
| 📐 [apioo/fusio-model](https://github.com/apioo/fusio-model) | **Data Models:** Shared schemas generated programmatically via [TypeSchema](https://typeschema.org/). |
| 📚 [apioo/fusio-docs](https://github.com/apioo/fusio-docs) | **Documentation:** The source markdown files for the documentation website. |
| 🛡️ [Backend App](https://github.com/apioo/fusio-apps-backend) | **Admin UI:** The Angular dashboard used to configure and monitor Fusio. |
| 👥 [Developer App](https://github.com/apioo/fusio-apps-developer) | **Dev Portal:** The default client-facing application portal for your API consumers. |

---

## ❤️ Support & Sponsorship

If you or your company rely on Fusio and would like to support its ongoing maintenance financially, please check out our sponsor panel:

👉 [Sponsor Fusio on GitHub](https://github.com/apioo/fusio)

We are thrilled to welcome you to the Fusio contributor community. Let’s build great API management tooling together! 🚀
