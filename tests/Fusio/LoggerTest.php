<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio;

/**
 * LoggerTest
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class LoggerTest extends \PHPUnit_Framework_TestCase
{
    public function testLog()
    {
        $body = $this->getMockBuilder('Psr\Http\Message\StreamInterface')
            ->getMock();

        $request = $this->getMockBuilder('PSX\Http\RequestInterface')
            ->getMock();

        $request->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue(['Content-Type' => ['application/json']]));

        $request->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue($body));

        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $connection->expects($this->once())
            ->method('insert')
            ->with($this->equalTo('fusio_log'), $this->equalTo([
                'appId'     => 1,
                'routeId'   => 1,
                'ip'        => '127.0.0.1',
                'userAgent' => null,
                'method'    => null,
                'path'      => null,
                'header'    => 'Content-Type: application/json',
                'body'      => null,
                'date'      => date('Y-m-d H:i:s'),
            ]));

        $connection->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue(1));

        $logger = new Logger($connection);
        $logId  = $logger->log(1, 1, '127.0.0.1', $request);

        $this->assertEquals(1, $logId);
    }

    public function testAppendError()
    {
        $connection = $this->getMockBuilder('Doctrine\DBAL\Connection')
            ->disableOriginalConstructor()
            ->getMock();

        $connection->expects($this->once())
            ->method('insert')
            ->with($this->equalTo('fusio_log_error'), $this->callback(function($row){
                $this->assertEquals(1, $row['logId']);
                $this->assertEquals('foo', $row['message']);

                return true;
            }));

        $logger = new Logger($connection);
        $logger->appendError(1, new \Exception('foo'));
    }
}
