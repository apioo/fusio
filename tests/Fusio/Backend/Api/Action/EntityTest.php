<?php

namespace Fusio\Backend\Api\Action;

use PSX\Http\Request;
use PSX\Http\Response;
use PSX\Http\Stream\TempStream;
use PSX\Test\ControllerDbTestCase;
use PSX\Test\Environment;
use PSX\Url;

class EntityTest extends ControllerDbTestCase
{
	public function getDataSet()
	{
		return $this->createFlatXMLDataSet(__DIR__ . '/../../../fixture.xml');
	}

	public function testGet()
	{
		$response = $this->sendRequest('http://127.0.0.1/backend/action/2', 'GET', array(
			'User-Agent'    => 'Fusio TestCase', 
			'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
		));

		$body   = (string) $response->getBody();
		$expect = <<<'JSON'
{
    "id": 2,
    "name": "Sql-Fetch-Row",
    "class": "Fusio\\Action\\SqlFetchRow",
    "config": {
        "connection": 1,
        "sql": "SELECT * FROM foo"
    }
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
		$this->assertJsonStringEqualsJsonString($expect, $body, $body);
	}

	public function testPost()
	{
		$response = $this->sendRequest('http://127.0.0.1/backend/action/2', 'POST', array(
			'User-Agent'    => 'Fusio TestCase', 
			'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
		), json_encode([
			'name'   => 'Foo',
			'class'  => 'Fusio\Action\SqlFetchRow',
			'config' => [
				'connection' => 1,
				'sql'        => 'SELECT * FROM foo'
			],
		]));

		$body = (string) $response->getBody();

		$this->assertEquals(405, $response->getStatusCode(), $body);
	}

	public function testPut()
	{
		$response = $this->sendRequest('http://127.0.0.1/backend/action/2', 'PUT', array(
			'User-Agent'    => 'Fusio TestCase', 
			'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
		), json_encode([
			'name'   => 'Bar',
			'config' => [
				'connection' => 2,
				'sql'        => 'SELECT * FROM bar'
			],
		]));

		$body   = (string) $response->getBody();
		$expect = <<<'JSON'
{
    "success": true,
    "message": "Action successful updated"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
		$this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'name', 'class', 'config')
            ->from('fusio_action')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(2, $row['id']);
        $this->assertEquals('Bar', $row['name']);
        $this->assertEquals('Fusio\Action\SqlFetchRow', $row['class']);
        $this->assertEquals('a:2:{s:10:"connection";i:2;s:3:"sql";s:17:"SELECT * FROM bar";}', $row['config']);
	}

	public function testDelete()
	{
		$response = $this->sendRequest('http://127.0.0.1/backend/action/2', 'DELETE', array(
			'User-Agent'    => 'Fusio TestCase', 
			'Authorization' => 'Bearer da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf'
		));

		$body   = (string) $response->getBody();
		$expect = <<<'JSON'
{
    "success": true,
    "message": "Action successful deleted"
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $body);
		$this->assertJsonStringEqualsJsonString($expect, $body, $body);

        // check database
        $sql = Environment::getService('connection')->createQueryBuilder()
            ->select('id', 'name', 'class', 'config')
            ->from('fusio_action')
            ->orderBy('id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(1)
            ->getSQL();

        $row = Environment::getService('connection')->fetchAssoc($sql);

        $this->assertEquals(1, $row['id']);
	}
}
