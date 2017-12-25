
# Testing

Fusio provides a complete Test-Setup for your API endpoints. For the test case
we use an in-memory sqlite database which contains the schema defined in the
`resources/migration` folder. In the `Fixture.php` class it is also possible to 
define fixture data which is inserted for every test case.

The idea is that each endpoint has a corresponding test case class which tests
the GET, POST, PUT and DELETE method of the resource. Internally we can send an 
HTTP request to Fusio without the need to setup an HTTP server this in 
combination with the in-memory sqlite database makes these tests very fast and 
efficient.
