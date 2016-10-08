
### 0.4.0

* Moved logic to the fusio engine and extracted adapter and connection 
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
