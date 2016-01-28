
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
