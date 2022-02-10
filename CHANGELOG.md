
### 2.1.9

* Use symfony mailer

### 2.1.8 (2022-21-01)

* Moved login provider keys to config
* Improve routes provider import

### 2.1.7 (2021-12-30)

* Remove schema traverse max recursion check

### 2.1.6 (2021-12-21)

* Add stripe adapter #428
* Fix SqlInsert action handle data without schema #423
* Add util redirect adapter
* Update developer portal
* Add consumer log endpoint

### 2.1.5 (2021-11-27)

* Improve cli adapter add uri fragment, query and header values as env values #421
* Add OAuth2 connection handling
* Add insomnia importer and improve postman import
* Improve error messages when importing a schema

### 2.1.4 (2021-10-06)

* Use thrift http client to connect to the worker

### 2.1.3 (2021-10-05)

* Fix installer remove app migration
* Add flush command to write cron file

### 2.1.2 (2021-10-04)

* Add wait for command
* Handle empty fusio deploy config
* Remove sample todo endpoint #403

### 2.1.1 (2021-10-02)

* Update dependencies
* Add HttpComposition and HttpLoadBalancer action #391
* Place CLI access token to current project folder to better handle multiple projects on a single server

### 2.1.0 (2021-08-07)

* Added worker system and actions to support different programming languages
* Added Java, Javascript, PHP and Python worker

### 2.0.4 (2021-07-15)

* Added VSCode Fusio plugin
* Added Laravel PHP SDK
* Improved PHP/Javascript SDK
* Move documentation to backend #400
* Allow OAuth2 app key/secret on client credentials flow #386

### 2.0.3 (2021-04-24)

* Add postman routes provider which allows to import a postman export
* Improve CORS handling for error responses
* Update composer dependencies

### 2.0.2 (2021-02-24)

* Fix async action execution command
* Fix documentation endpoint return correct export links
* About response add additional links and categories and scopes

### 2.0.1 (2021-02-09)

* Fix installer env quoting #365

### 2.0.0 (2021-02-06)

* Updated client SDKs
* Moved apps folder to public folder

### 2.0.0-RC2 (2021-01-15)

* It is now possible to deploy changes to a remote Fusio instance #356 
* Moved all CLI commands to a separate package fusio/cli #357
* Moved all generated model classes to a separate package fusio/model 
* Execute all internal periodic tasks through the cron system
* Added role concept, a role is assigned to a user and specifies the scopes and category 
* Make category editable, this allows to create new categories for your app
* Consolidate OAuth2 endpoints
* An adapter can now register only provider classes
* Removed webserver config generation

### 2.0.0-RC1 (2020-12-28)

* Migrated all backend controller to actions
* Reorganized endpoints
* Improved RPC support
* Add option to execute an action async
* Migrated all schemas to TypeSchema
* Schema based on PHP class
* Improved backend app
* Redesigned adapter actions
* Removed migrations and moved all tables to InnoDB
* Set PHP 7.2 as min requirement and support 8.0
* Add properties to passthru record #316
* Add unique request id to the response header #298

### 1.9.4 (2020-06-20)

* Add driver option to env file
* Add A/B test action #314
* Add JSON patch action #315
* Add util cache action #313

### 1.9.3 (2020-05-09)

* Migrated all apps to a repo with a fusio-apps prefix
* Removed backend app from repo and install backend app through marketplace on
  install
* Moved apps from public folder into a dedicated apps folder
* Added no SSL verify option to marketplace install/update command
* Add .htaccess to root dir which redirect all requests to the public/ folder

### 1.9.2 (2020-04-18)

* Developer app add user activation
* Developer app add password reset #303
* Fix and improve user activation and password reset API endpoint
* Add captcha check to password reset endpoint
* Allow dot in event name
* Fix deletion of active routes #300

### 1.9.1 (2020-04-05)

* Improved the routes overview at the backend app
* Action constructor dependency injection #301
* Publish internal events through webhooks feature #288
* Add option to deploy a schema class #251
* SqlTable provider generate schema based on the table schema #294
* Add Laravel and Symfony adapter #246
* Add PHP version 7.4 to all test cases
* Make schema TypSchema compatible

### 1.9.0 (2020-02-04)

* Add rest password process for users which have registered through email. If
  the process the user receives an email containing a link with a token which
  can be used to change the current password
* Renamed column period to period_type for MariaDB compatibility #252
* Return refresh token for simple login and add option extend an existing
  access token #264
* Restructured clean command to remove expired database entries i.e. app token
  instead of removing the demo files
* Added specific scopes to backend/consumer endpoints to allow access to
  only specific parts of the backend/consumer API
* Add option to request concrete scopes for backend and consumer authorization
* Expired JWT return proper 401 status code #268

### 1.8.0 (2019-09-18)

* Add SQL select action #260
* Add option to download the SDK #259
* Add log rotate command to copy all logs to an archive table #256
* Add marketplace to backend #254
* Update doctrine/dbal to 2.9 #252

### 1.7.0 (2019-08-12)

* Add discover route endpoint #249
* Add command to clear the cache #243
* Add GraphQL proxy #238
* Add option to specify an operation id for each method #236
* Pre-configured routes #233
* Added new vscode app which allows to edit PHP sandbox actions #191
* Add RPC endpoint to execute actions directly #167
* Fix issue in SqlTable action to handle null values #239

### 1.6.0 (2019-06-01)

* Added missing entity id to all backend endpoint schemas
* Improved monetization system added contract and invoice to plan #217
* Updated backend and consumer app
* CLI action add command handle optional arguments #230
* Custom user attributes #226
* Add option to get information about the current token i.e. scopes inside an
  action
* PHP Sandbox allow the definition of functions
* Update dotenv dependency
* HTTP adapter redirect variable path fragments

### 1.5.1 (2019-02-09)

* Add health endpoint to check whether every connection works. This can be
  useful for docker container or other monitoring solutions
* Store registered provider classes in database since under docker we should
  not write to the filesystem
* Added new default connections: Ftp, GraphQL, Smtp and Soap
* Removed v8 adapter

### 1.5.0 (2019-01-01)

* Use connection for sending SMTP mails #197
* Make pub/sub system more customizable #196
* Set correct CORS header if an exception occurs
* Use JWT as access token #198
* Fix add costs field to the routes schema
* Improve SQL-Table action add possibility to specify default settings and
  return proper types on all db drivers
* Migrated all unit tests to PHPUnit 6.0
* Set all travis tests to check PHP 7.1, 7.2 and 7.3
* Minimum PHP requirement is now 7.1

### 1.4.0 (2018-10-28)

* Added payment system to monetize the API #174
* Added user provider support to easily implement other OAuth2 provider #190
* Extended dashboard and statistics
* Added provider.php config file which contains PHP classes to extend Fusio
* Add deploy info in case web server config could not be written
* Updated fusio backend and swagger-ui app

### 1.3.1 (2018-09-01)

* Improve serialization formats store all data json encoded instead of PHP
  serialized
* Make Fusio compatible with multiple database vendors and renamed camel case
  database columns to snake case columns #178

### 1.3.0 (2018-07-21) 

* Moved to Doctrine DBAL Migrations system. This is a BC since the old
  migrations are not used any more. Because of this it is also possible to use
  Fusio with a single database which contains Fusio and the App tables
* Converted example todo endpoint from PHP files to classes
* Its not longer possible to rename existing Connections since they are
  referenced in other resources
* Add an option to store an arbitrary UI vocabulary to argument the
  JsonSchema #166
* Add `/export/schema/:name` endpoint which allows to access any JsonSchema and
  UI vocabulary from the public
* Add Link header on OPTIONS request which link to the fitting export schema
  endpoint, this allows us in the future to build apps which can automatically
  discover the create and update form of an endpoint 
* Add OAuth2 client credentials grant to obtain an access token based on the
  app key and secret #172

### 1.2.1 (2018-06-27)

* Fixed a critical bug in the authorization middleware #164
* Sql action add config to set default limit #162
* Update backend app add PHP sandbox autocomplete, fix automatic logout and
  update deps #160
* Truncate large exception messages on logging middleware

### 1.2.0 (2018-06-09)

* Return proper 429 status code and error response in case the rate limit is
  reached
* Add subscription support #152
* Fix /whoami and /revoke endpoint not sending proper CORS headers #153
* Add PHP sandbox action #69
* Add installer script to install Fusio also without access to the CLI
* Backend app add option to provide custom action class
* Consumer app add subscription support to allow customers to register HTTP
  callbacks
* Updated backend and consumer app to Angular 1.7
* User registration fix handling recaptcha verification
* Reorganized consumer app and grant endpoints

### 1.1.3 (2018-05-11)

* Add description field to method
* Add scopes field to route
* Add additional meta fields to OpenAPI and Swagger spec
* Add security fields to the Swagger spec
* Updated swagger-ui app

### 1.1.2 (2018-04-08)

* Fix handling of OPTIONS request without GET schema #142
* Increase name column length #141
* Add option to deploy command to force re-execution of migration files #140
* Add clean command #137
* Add push command to push the Fusio instance to a remote provider

### 1.1.1 (2018-03-25)

* Add web server config generator #132
* Update fusio backend app
* Update swagger-ui and backend app
* Importer parses now also OpenAPI and Swagger YAML format #115
* Improved CORS handling and removed config

### 1.1.0 (2018-03-04)

* Update dependencies
* Add priority to routes so that the order of the routes in deployment is 
  stable #120
* Installer add preview option to show SQL queries #74
* Add connection lifecycle and deployment interface to give a connection the 
  possibility to execute additional logic on execution
* Improved documentation

### 1.0.0 (2018-01-13)

* Updated dependencies
* Improved tests and documentation

### 1.0.0-RC8 (2018-01-03)

* Update PSX framework to version 4.0
* Add rate limit to deployment
* Update developer app
* Improve tests and use migration as fixture

### 1.0.0-RC7 (2017-12-23)

* Use dotenv config to load sensitive values from environment variables #110
* Add missing help files

### 1.0.0-RC6 (2017-12-14)

* Update developer app
* Summarize deploy status and improve deploy output #108
* Improve deploy schema file resolving #107

### 1.0.0-RC5 (2017-12-09)

* Documentation handle schema array types #106
* Fix logging of long request uris #105
* Add config to specify expire time of refresh token #104
* Allow schema scheme when deploying a json schema #103
* Add upgrade check
* Improve test cases
* Add PHP 7.2 support

### 1.0.0-RC4 (2017-11-27)

* Backend app fix add responses
* Improved http engine action
* Allow array json structure in response #81

### 1.0.0-RC3 (2017-11-19)

* Fix materialize resource collection

### 1.0.0-RC2 (2017-11-18)

* Issue 401 http response code if access token is not valid #97
* Add user_approval setting to control whether a user needs to verify an 
  account through email #83
* Add swagger-ui as app
* Improve generated OpenAPI spec add security scope
* Add listing filter to show only user routes by default
* Trigger app.remove_token event on /authorization/revoke endpoint #94
* Improved HTTP adapter add a content-type config field and parse form encoded 
  responses #92
* Fix proper handling of null values #84
* Add header field to action designer #89
* Backend scope load all assigned routes #90
* Added apache example conf and update docs

### 1.0.0-RC1 (2017-11-01)

* Add action execute command #64
* User service add check whether email already exists #80
* User API proper handling of OPTIONS and HEAD requests #82
* Fix update correct handling of changed backend routes #85
* Internal API add config option to send CORS header

### 0.9.9 (2017-10-15)

* Update OAuth2 token endpoint handle OPTIONS request
* Add query parameters to backend schema
* Backend endpoints handle count parameter #67
* Relaxed password requirements and make min pw length configurable #68
* Allow email as user name login #66

### 0.9.8 (2017-10-11)

* Add config.yaml to deploy
* Add system restore command
* Improve HEAD and OPTIONS method handling 
* Cronjob handle errors

### 0.9.7 (2017-10-05)

* Add cronjob service
* Add a schema to every backend endpoint
* Fix route path regexp validation #63
* Update API doc

### 0.9.6 (2017-09-25)

* Improved documentation add backend api reference and action examples
* Add PATCH method support
* Update dependencies and prepare release

### 0.9.5 (2017-09-09)

* Add scopes per route #55
* Add OAuth2 refresh token endpoint #16
* Added static file processor
* Case insensitive env vars replacement

### 0.9.4 (2017-08-26)

* Added automatic engine detection
* Improved backend app
* Log execution time of an action and add new statistics
* Added openapi generation and import support
* Handle schema for query parameters
* Improved route serialization #44
* Handle multiple responses for different status codes
* Split up deploy file into separate files
* Removed routes action and schema relation handling
* Removed old upgrade paths
* Execute migration on an empty database schema

### 0.9.3 (2017-07-20)

* Deploy command handle verbose mode
* Add general update code which inserts new routes on update
* Add statistic request count method

### 0.9.2 (2017-07-09)

* Add audit panel which contains a log from every action on the system
* Added event handler

### 0.9.1 (2017-06-28)

* Rename default namespace
* Update docs
* Add action engine parameter to export

### 0.9.0 (2017-06-11)

* Improved deploy service
* Changed default todo sample API to php file engine
* Option to set config values in the deploy file
* Add action resolver which determines the engine which is used i.e. php or v8
* Move routes config handling to a separate service

### 0.8.0 (2017-05-25)

* Update api documentation app
* Add oauth2 filter also for public endpoints if authorization header is 
  available
* Improve method selection performance
* Added migration command which lists all executed migrations

### 0.7.4 (2017-04-29)

* Deploy config allow schema include and class name as action in a route

### 0.7.3 (2017-04-19)

* Updated default deployment configuration
* Access env vars in deploy configuration
* Handle non empty connection passwords in the deploy config #37
* Extended manual

### 0.7.2 (2017-04-08)

* Added deploy migration

### 0.7.1 (2017-03-26)

* Added deploy command
* Add issued token statistic
* Add app token endpoint
* The protected API endpoints accept now also a JWT as bearer token which was
  obtained by the /consumer/login endpoint
* Added consumer endpoint documentation test cases

### 0.7.0 (2017-02-24)

* Passthu schema fix handling empty request body
* Updated v8 adapter
* Updated manual

### 0.6.9 (2017-02-19)

* Update v8 adapter
* Connection test catch all exceptions and throw a http error

### 0.6.8 (2017-02-12)

* Connection try to connect to remote service on creation
* Improve designer css
* Update dependencies

### 0.6.7 (2017-02-07)

* Add http connection
* Add schema and action designer
* Update psx v8 component

### 0.6.6 (2017-01-31)

* Register command add auto confirm option

### 0.6.5 (2017-01-29)

* Fixed missing nav template

### 0.6.4 (2017-01-29)

* Disable editing the default scopes
* Added error panel
* Redesigned backend ui and grouped main navigation into categories

### 0.6.3 (2017-01-22)

* Update v8 adapter

### 0.6.2 (2017-01-22)

* Fixed user system check
* Transition backend app to browserify and remove bower_components

### 0.6.1 (2017-01-18)

* Added config tag form
* Action and connection config allow array values
* Update json schema preview style
* Add system status check command
* Removed randomlib and use random_bytes to generate token

### 0.6.0 (2017-01-01)

* Use chartjs 2.0
* Use psx 3.0 components
* Moved backend app to fusio/ folder to use less potential API paths

### 0.5.0 (2016-11-17)

* Added v8 processor action
* Cleaned up engine
* Removed database panel
* Added swagger import

### 0.4.1 (2016-11-02)

* Added rate limit
* Improved and extended console commands
* Improved backend app
* Added test cases

### 0.4.0 (2016-10-09)

* Moved logic to the fusio engine and extracted action and connection 
  implementation
* Improved backend ui use Robot font

### 0.3.5 (2016-09-17)

* Renamed consumer app to developer and improved the app
* Added SQL table action
* Option to provide the request method when testing an action

### 0.3.4 (2016-08-25)

* Streamlined backend ui
* Improved route lookup speed 
* Backend add global loading animation 
* Fixed detail and schema import/export command
* Added dialog to test an action
* Database ui add auto increment option
* Add repeat password question to adduser command

### 0.3.3 (2016-07-31)

* Added TryCatch action
* SQL-Connection set utf8 charset and disable emulated prepared statements for 
  mysql
* Added more template filters
* Added user object to context at the condition action
* Improve SQL-Builder action
* Add an option to provide examples for each API endpoint to the documentation
* Unified url configuration for the backend, consumer and documentation app
* Add overview doc
* Add config help

### 0.3.2 (2016-07-23)

* Fix route deployment to live

### 0.3.1 (2016-07-22)

* Consumer add documentation link
* Fix consumer navigation

### 0.3.0 (2016-07-20)

* Rewrote consumer implementation moved from jQuery to AngularJS
* Add backend database panel to create and edit database schemas
* Moved route configuration into a seperate table and add deploy mechanism which
  copies all route configurations if the API status is set to production 
* Add backend configuration
* Add sqlbuilder action to build a response based on multiple SQL queries
* Improved docs
* Add user to context
* Upgrade backend app to angular 1.5, angular-ui 1.2 and bootstrap 3.3
* Add parameters field to app
* Upgrade PSX to 2.0

### 0.2.2 (2016-02-27)

* Add system import/export command
* Add user to context
* Add jsonschema import and generate access token command
* Improved services to check whether a resource already exists

### 0.2.1 (2016-01-28)

* Added documentation and improved help docs
* Moved backend code into seperate composer project
* Encrypt connection credentials in database
* Added a access token generation method to the app service which is used 
  globally
* Add support for implicit grant to simplify the use of Fusio for js 
  applications
* Action cache consider also uri fragments and parameters in cache key
* Removed client credentials grant and use instead password grant

### 0.2.0 (2016-01-02)

* Added consumer API and javascript client implementation
* Improved code architecture
* Added RAML import
* Added json schema export command
* Generate more secure tokens and passwords
* Fixed a security issue where the installer installs a test admin account with
  a fix username and password

### 0.1.7 (2015-11-26)

* Added adapter system
* Changing a action or schema which are used by a route in production status is
  not longer possible
* Added and improved various actions
* Improve access token generator
* Moved action and connection class definition from config file into a seperate 
  table

### 0.1.6 (2015-10-06)

* Improved backend modal dialogs
* Add export schema into different formats
* Update API documentation viewer #10
* Add row exists filter

### 0.1.5 (2015-09-12)

* Added javascript angular protractor e2e tests 
* Add documentation api test case
* Add change password panel #1
* Fix query filter and add test case
* Fix log pagination #8

### 0.1.4 (2015-08-26)

* Improved installer
* Add statistic panel to backend
* Log errors which occur in an action and display them in the log details

### 0.1.3 (2015-08-11)

* Redirect DisplayException if thrown in template context
* Fix js user controller remove apps data before update
* Extended test cases
* Check that provided jsonschema is an object type
* Make dashboard chart more responsive

### 0.1.2 (2015-08-04)

* Add more test cases
* Add database version

### 0.1.1 (2015-08-04)

* Update help and fix help references
* Fixed handling action and connection config parameters

### 0.1.0 (2015-07-30)

* Initial release
