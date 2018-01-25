
Testing
=======

Fusio provides a complete Test-Setup for your API endpoints. For the test case
we use an in-memory sqlite database which contains the schema defined in the
``resources/migration`` folder. In the ``Fixture.php`` class it is also possible 
to define fixture data which is inserted for every test case.

The idea is that each endpoint has a corresponding test case class which tests
the GET, POST, PUT and DELETE method of the resource. Internally we can send an 
HTTP request to Fusio without the need to setup an HTTP server. This makes these 
tests very fast and efficient.

The Method ``Fixture::getPhpUnitDataSet`` returns the data set which is inserted
for every test case. There we insert a fixed access token with the fitting 
rights so that we can call our protected API endpoints.

You can execute those tests inside the Fusio directory root with a simple 
phpunit command (because of the available ``phpunit.xml`` configuration):

.. code-block:: text

    phpunit

Development
-----------

Every test case should extend from the ``ApiTestCase`` class. The test case
contains a test method for every HTTP method i.e. ``testGet``, ``testPost``, etc.
Every test makes the appropriated call to the API endpoint. Then we assert the
response body and if needed also the headers (for larger response bodies it is
recommended to move the expected JSON payload to an external file which is then
included i.e. through ``file_get_contents``). Through this way we can simply 
assure that our API works as expected. The following shows a simple API test 
case from the example todo entity API endpoint:

.. code-block:: php

    <?php

    class EntityTest extends ApiTestCase
    {
        public function testGet()
        {
            $response = $this->sendRequest('/todo/4', 'GET', [
                'User-Agent'    => 'Fusio TestCase',
            ]);
    
            $actual = (string) $response->getBody();
            $actual = preg_replace('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', '0000-00-00 00:00:00', $actual);
            $expect = <<<'JSON'
    {
        "id": "4",
        "status": "1",
        "title": "Task 4",
        "insertDate": "0000-00-00 00:00:00"
    }
    JSON;
    
            $this->assertEquals(200, $response->getStatusCode(), $actual);
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
        }
    
        public function testPost()
        {
            $response = $this->sendRequest('/todo/4', 'POST', [
                'User-Agent'    => 'Fusio TestCase',
            ]);
    
            $actual = (string) $response->getBody();
            $expect = <<<'JSON'
    {
        "success": false,
        "title": "Internal Server Error",
        "message": "Given request method is not supported"
    }
    JSON;
    
            $this->assertEquals(405, $response->getStatusCode(), $actual);
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
        }
    
        public function testPut()
        {
            $response = $this->sendRequest('/todo/4', 'PUT', [
                'User-Agent'    => 'Fusio TestCase',
            ]);
    
            $actual = (string) $response->getBody();
            $expect = <<<'JSON'
    {
        "success": false,
        "title": "Internal Server Error",
        "message": "Given request method is not supported"
    }
    JSON;
    
            $this->assertEquals(405, $response->getStatusCode(), $actual);
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
        }
    
        public function testDelete()
        {
            $response = $this->sendRequest('/todo/4', 'DELETE', [
                'User-Agent'    => 'Fusio TestCase',
                'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
            ]);
    
            $actual = (string) $response->getBody();
            $expect = <<<'JSON'
    {
        "success": true,
        "message": "Delete successful"
    }
    JSON;
    
            $this->assertEquals(200, $response->getStatusCode(), $actual);
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    
            /** @var \Doctrine\DBAL\Connection $connection */
            $connection = Environment::getService('connector')->getConnection('Default-Connection');
            $actual = $connection->fetchAssoc('SELECT id, status, title FROM app_todo WHERE id = 4');
            $expect = [
                'id' => 4,
                'status' => 0,
                'title' => 'Task 4',
            ];
    
            $this->assertEquals($expect, $actual);
        }
    
        public function testDeleteWithoutAuthorization()
        {
            $response = $this->sendRequest('/todo/4', 'DELETE', [
                'User-Agent'    => 'Fusio TestCase',
            ]);
    
            $actual = (string) $response->getBody();
            $expect = <<<'JSON'
    {
        "success": false,
        "title": "Internal Server Error",
        "message": "Missing authorization header"
    }
    JSON;
    
            $this->assertEquals(401, $response->getStatusCode(), $actual);
            $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
        }
    }
