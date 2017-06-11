
### 0.9.0

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
