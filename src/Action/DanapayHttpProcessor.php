<?php
/*
 * Fusio is an open source API management platform which helps to create innovative API solutions.
 * For the current version and information visit <https://www.fusio-project.org/>
 *
 * Copyright 2015-2023 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Action;

use Fusio\Adapter\Http\Action\HttpSenderAbstract;
use Fusio\Engine\ConfigurableInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\Exception\ConfigurationException;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Environment\HttpResponseInterface;
use Fusio\Engine\Request\HttpRequestContext;
use PSX\Record\Transformer;
use GuzzleHttp\Client;
use PSX\Http\MediaType;

/**
 * HttpProcessor
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @link    https://www.fusio-project.org/
 */
class DanapayHttpProcessor extends HttpSenderAbstract implements ConfigurableInterface
{
    private ?Client $client = null;

    public function getName(): string
    {
        return 'Danapay-HTTP-Processor';
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): HttpResponseInterface
    {
        $url = $configuration->get('url');
        if (empty($url)) {
            throw new ConfigurationException('No url configured');
        }

        $type = $configuration->get('type');
        $version = $configuration->get('version');
        $authorization = $configuration->get('authorization');

        $requestContext = $request->getContext();
        if ($requestContext instanceof HttpRequestContext) {
            $httpRequest = $requestContext->getRequest();
            $uri = $httpRequest->getUri();
            $url = $url . $uri->getPath();
        }

        return $this->send(
            $url,
            $type,
            $version,
            $authorization,
            $request,
            $context
        );
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory): void
    {
        $builder->add($elementFactory->newInput('url', 'URL', 'text', 'An url to the HTTP endpoint'));
        $builder->add($elementFactory->newSelect('type', 'Content-Type', self::CONTENT_TYPE, 'The content type which you want to send to the endpoint.'));
        $builder->add($elementFactory->newSelect('version', 'HTTP Version', self::VERSION, 'Optional HTTP protocol which you want to send to the endpoint.'));
        $builder->add($elementFactory->newInput('authorization', 'Authorization', 'text', 'Optional a HTTP authorization header which gets passed to the endpoint.'));
    }

    public function send(string $url, ?string $type, ?string $version, ?string $authorization, RequestInterface $request, ContextInterface $context): HttpResponseInterface
    {
        $requestContext = $request->getContext();
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        /**
         * @var Doctrine\DBAL\Connection $connection
         */
        $connection = $this->connector->getConnection('System');

        if ($requestContext instanceof HttpRequestContext) {
            $httpRequest = $requestContext->getRequest();
            $httpRequest->setHeader('X-API-SECRET', '5cdf808c1f8286019694325935');
        
            $exclude = ['accept', 'accept-charset', 'accept-encoding', 'accept-language', 'connection', 'content-type', 'host', 'user-agent'];
            $headers = $httpRequest->getHeaders();
            $headers = array_diff_key($headers, array_combine($exclude, array_fill(0, count($exclude), null)));

            $method = $httpRequest->getMethod();
            $uriFragments = $requestContext->getParameters();
            $query = $httpRequest->getUri()->getParameters();
            $host = $httpRequest->getHeader('Host');
            $proxyAuthorization = $httpRequest->getHeader('Proxy-Authorization');
        } else {
            $method = 'POST';
            $uriFragments = [];
            $query = [];
            $headers = [];
            $host = null;
            $proxyAuthorization = null;
        }

        $headers['x-fusio-operation-id'] = '' . $context->getOperationId();
        $headers['x-fusio-user-anonymous'] = $context->getUser()->isAnonymous() ? '1' : '0';
        $headers['x-fusio-user-id'] = '' . $context->getUser()->getId();
        $headers['x-fusio-user-name'] = $context->getUser()->getName();
        $headers['x-fusio-app-id'] = '' . $context->getApp()->getId();
        $headers['x-fusio-app-key'] = $context->getApp()->getAppKey();
        $headers['x-fusio-remote-ip'] = $clientIp;
        $headers['x-forwarded-for'] = $clientIp;
        $headers['accept'] = 'application/json, application/x-www-form-urlencoded;q=0.9, */*;q=0.8';

        $bearer_token = str_replace("Bearer ", "", $headers['authorization'][0]);

        if (!empty($host)) {
            $headers['x-forwarded-host'] = $host;
        }

        if (!empty($authorization)) {
            $headers['authorization'] = $authorization;
        } elseif (!empty($proxyAuthorization)) {
            $headers['authorization'] = $proxyAuthorization;
        }

        if(!preg_match("/auth\/byEmail/", $url)) {
            //access token pour entrer dans danapay-api
            $map = $connection->fetchAssociative('SELECT dp_token FROM dp_token_maps WHERE fusio_token = :fusio_token', [
                'fusio_token' => $bearer_token
            ]);

            if($map)  {
                $headers['authorization'] = 'Bearer ' . $map['dp_token'];
            }
        }

        $options = [
            'headers' => $headers,
            'query' => $query,
            'http_errors' => false,
        ];

        if (!empty($version)) {
            $options['version'] = $version;
        }

        if ($type == self::TYPE_FORM) {
            $options['form_params'] = Transformer::toArray($request->getPayload());
        } else {
            $options['json'] = $request->getPayload();
        }

        if (!empty($uriFragments)) {
            foreach ($uriFragments as $name => $value) {
                $url = str_replace(':' . $name, $value, $url);
            }
        }

        $client      = $this->client ?? new Client();
        $response    = $client->request($method, $url, $options);
        $contentType = $response->getHeaderLine('Content-Type');
        $response    = $response->withoutHeader('Content-Type');
        $response    = $response->withoutHeader('Content-Length');
        $body        = (string) $response->getBody();

        if ($this->isJson($contentType)) {
            $data = json_decode($body);
        } elseif (str_contains($contentType, self::TYPE_FORM)) {
            $data = [];
            parse_str($body, $data);
        } else {
            if (!empty($contentType)) {
                $response = $response->withHeader('Content-Type', $contentType);
            }

            $data = $body;
        }

        $response = $response->withoutHeader('Access-Control-Allow-Origin');

        if(isset($data->access_token)) {
            //save the return access token and link it to a new fusio access_token
            $connection->insert('dp_token_maps', [
                'fusio_token' => $bearer_token,
                'dp_token' => $data->access_token,
                'date' => date('Y-m-d H:i:s')
            ]);

            //access token pour entrer dans fusio - for webapp usage
            $data->access_token = $bearer_token;
        }

        $result = $this->response->build(
            $response->getStatusCode(),
            $response->getHeaders(),
            $data
        );

        return $result;
    }

    private function isJson(?string $contentType): bool
    {
        if (!empty($contentType)) {
            try {
                return MediaType\Json::isMediaType(MediaType::parse($contentType));
            } catch (\InvalidArgumentException $e) {
            }
        }

        return false;
    }
}
