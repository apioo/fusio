<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <k42b3.x@gmail.com>
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

namespace Fusio\Impl\Adapter\Transform;

use Fusio\Impl\Adapter\TransformInterface;
use InvalidArgumentException;
use PSX\Json;
use PSX\Uri;
use PSX\Util\CurveArray;
use Symfony\Component\Yaml\Parser;

/**
 * Raml
 *
 * @author  Christoph Kappestein <k42b3.x@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Raml implements TransformInterface
{
    protected $parser;
    protected $version;
    protected $schemas;

    protected $routes;
    protected $schema;

    public function __construct(Parser $parser = null)
    {
        $this->parser = $parser ?: new Parser();
    }

    public function transform($data)
    {
        $data = $this->parser->parse($data);

        if (!is_array($data)) {
            throw new InvalidArgumentException('Invalid RAML schema');
        }

        $this->version = $this->parseVersion($data);

        if (isset($data['baseUri'])) {
            $baseUri  = new Uri($data['baseUri']);
            $basePath = $baseUri->getPath();
        } else {
            $basePath = '/';
        }

        if (isset($data['schemas']) && is_array($data['schemas'])) {
            $this->schemas = $this->parseSchemas($data['schemas']);
        }

        $this->parsePaths($basePath, $data);

        return [
            'routes' => $this->routes,
            'schema' => $this->schema,
        ];
    }

    protected function parsePaths($basePath, array $data)
    {
        if (is_array($data)) {
            foreach ($data as $path => $value) {
                if (substr($path, 0, 1) == '/') {
                    $this->parsePath($basePath . '/' . $path, $value);
                }
            }
        }
    }

    protected function parsePath($path, array $data)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE'];
        $path    = $this->normalizePath($path);
        $config  = [];

        $data = array_change_key_case($data, CASE_UPPER);

        foreach ($methods as $method) {
            if (isset($data[$method])) {
                $config[] = $this->parseMethod($method, $data[$method], $path);
            }
        }

        $this->parsePaths($path, $data);

        $this->routes[] = [
            'methods' => implode('|', $methods),
            'path'    => $path,
            'config'  => [[
                'active'  => true,
                'status'  => 4,
                'name'    => (string) $this->version,
                'methods' => $config,
            ]],
        ];
    }

    protected function parseMethod($methodName, array $data, $path)
    {
        if (isset($data['body'])) {
            $name    = $this->normalizeName($path . '-' . $methodName . '-request');
            $request = $this->parseSchema($data['body'], $name);

            if (empty($request)) {
                throw new InvalidArgumentException('Found no JSONSchema for ' . $methodName . ' ' . $path . ' request');
            }
        } else {
            $request = 'Passthru';
        }

        if (isset($data['responses']) && is_array($data['responses'])) {
            $codes = [200, 201];
            $body  = null;

            foreach ($codes as $code) {
                if (isset($data['responses'][$code]['body'])) {
                    $body = $data['responses'][$code]['body'];
                    break;
                }
            }

            if (!empty($body)) {
                $name     = $this->normalizeName($path . '-' . $methodName . '-response');
                $response = $this->parseSchema($body, $name);

                if (empty($response)) {
                    throw new InvalidArgumentException('Found no JSONSchema for ' . $methodName . ' ' . $path . ' response');
                }
            } else {
                $response = 'Passthru';
            }
        } else {
            $response = 'Passthru';
        }

        $action = 'Welcome';

        return [
            'active'   => true,
            'public'   => true,
            'name'     => $methodName,
            'action'   => '${action.' . $action . '}',
            'request'  => '${schema.' . $request . '}',
            'response' => '${schema.' . $response . '}',
        ];
    }

    protected function parseSchema($data, $name)
    {
        foreach ($data as $mediaType => $row) {
            if ($mediaType == 'application/json' && is_array($row)) {
                $schema = isset($row['schema']) ? $row['schema'] : null;
                if (!empty($schema) && is_string($schema)) {
                    if (strpos($schema, '{') === false) {
                        // check whether we have a reference to a schema
                        if (isset($this->schemas[$schema])) {
                            $schema = $this->schemas[$schema];
                        }

                        // at the moment we cant resolve external files
                        if (substr($schema, 0, 8) == '!include') {
                            throw new InvalidArgumentException('It is not possible to include external files');
                        }
                    }

                    // check whether we have a json format and prettify
                    $schema = Json::encode(Json::decode($schema, false), JSON_PRETTY_PRINT);

                    $this->schema[] = [
                        'name'   => $name,
                        'source' => $schema,
                    ];

                    return $name;
                }
            }
        }

        return null;
    }

    protected function normalizePath($path)
    {
        $path = '/' . implode('/', array_filter(explode('/', $path)));
        $path = preg_replace('/(\{(\w+)\})/i', ':$2', $path);

        return $path;
    }

    protected function normalizeName($name)
    {
        $name = ltrim($name, '/');
        $name = str_replace('/', '-', $name);
        $name = preg_replace('/[^\dA-z0-9\-\_]/i', '', $name);

        return $name;
    }

    protected function parseVersion(array $data)
    {
        $version = 1;
        if (isset($data['version'])) {
            $version = ltrim($data['version'], 'v');
            $version = (int) $version;
            $version = $version > 0 ? $version : 1;
        }

        return $version;
    }

    protected function parseSchemas(array $schemas)
    {
        // @TODO handle !include in schemas

        if (CurveArray::isAssoc($schemas)) {
            return $schemas;
        } else {
            foreach ($schemas as $schemaList) {
                if (is_string($schemaList)) {
                } elseif (CurveArray::isAssoc($schemaList)) {
                    return $schemaList;
                }
            }
        }
    }
}
