<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use PSX\Framework\Test\Environment;
use PSX\Http\Client\ClientInterface;
use PSX\Http\Client\GetRequest;
use Symfony\Component\Yaml\Yaml;

/**
 * MarketplaceTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class MarketplaceTest extends TestCase
{
    /**
     * Checks whether every file in the marketplace repository is available and
     * every download works as expected
     */
    public function testRepository()
    {
        /** @var ClientInterface $httpClient */
        $httpClient = Environment::getService('http_client');
        $apps = Yaml::parse(file_get_contents(__DIR__ . '/../marketplace.yaml'));

        foreach ($apps as $name => $app) {
            $this->assertSame(1, version_compare($app['version'], '0.0'), $name);
            $this->assertNotEmpty($app['description'], $name);
            $this->assertEquals($app['screenshot'], filter_var($app['screenshot'], FILTER_VALIDATE_URL), $name);
            $this->assertEquals($app['website'], filter_var($app['website'], FILTER_VALIDATE_URL), $name);
            $this->assertEquals($app['downloadUrl'], filter_var($app['downloadUrl'], FILTER_VALIDATE_URL), $name);

            $response = $httpClient->request(new GetRequest($app['downloadUrl']));

            $file = __DIR__ . '/' . $name . '.zip';
            file_put_contents($file, (string) $response->getBody());

            $this->assertEquals(200, $response->getStatusCode());
            $this->assertEquals($app['sha1Hash'], sha1_file($file), $name);
            $this->assertTrue((new \ZipArchive())->open($file, \ZipArchive::CHECKCONS));
        }
    }
}
