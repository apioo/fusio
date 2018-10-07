
Social Login
============

Fusio provides a developer portal where consumers of your API can register and
create their apps. Besides the traditional sign-up via email and password Fusio
provides a system to allow 3rd party providers. By default Fusio supports:

* Facebook
* Google
* Github

But it is also easy possible to add other providers. The provider must support
OAuth2 in order to work with Fusio.

Flow
----

The javascript app starts the authentication process by redirecting the user to
the provider. I.e. the developer app uses the AngularJS satellizer module to
start this process. If the user returns, your app needs to send a POST request
to the endpoint ``/consumer/provider/google`` providing the following payload:

.. code-block:: json
    
    {
      "code": "",
      "clientId": ""
      "redirectUri": ""
    }

Then on the server side Fusio will try to obtain an access token using the code
and client id. Fusio knows also the client secret of the provider which you need
to provide at the ``.env`` file. If this was successful Fusio tries to get some
additional information about the user (this step depends always on the remote
provider how you get information about the user).

If everything went fine Fusio creates a new "remote" user entry (if the id does
not already exists) and returns directly an JWT which can be used in any 
subsequent API calls:

.. code-block:: json
    
    {
      "token": ""
    }

Implementation
--------------

If you want to add a new provider you need to create a class which implements
the ``Fusio\Engine\User\ProviderInterface``. Then you need to register this
class in your ``provider.php`` file. To give you an example how such a provider
might look please take a look at our Google provider:

.. code-block:: php
    
    <?php
    
    namespace Fusio\Impl\Provider\User;
    
    use Fusio\Engine\Model\User;
    use Fusio\Engine\User\ProviderAbstract;
    use Fusio\Impl\Base;
    use PSX\Http\Client\GetRequest;
    use PSX\Http\Client\PostRequest;
    use PSX\Json\Parser;
    use PSX\Uri\Url;
    use RuntimeException;
    
    /**
     * Google
     */
    class Google extends ProviderAbstract
    {
        /**
         * @inheritdoc
         */
        public function getId()
        {
            return self::PROVIDER_GOOGLE;
        }
    
        /**
         * @inheritdoc
         */
        public function requestUser($code, $clientId, $redirectUri)
        {
            $accessToken = $this->getAccessToken($code, $clientId, $this->secret, $redirectUri);
    
            if (!empty($accessToken)) {
                $url      = new Url('https://www.googleapis.com/plus/v1/people/me/openIdConnect');
                $headers  = [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'User-Agent'    => Base::getUserAgent()
                ];
    
                $response = $this->httpClient->request(new GetRequest($url, $headers));
    
                if ($response->getStatusCode() == 200) {
                    $data  = Parser::decode($response->getBody());
                    $id    = isset($data->sub) ? $data->sub : null;
                    $name  = isset($data->name) ? $data->name : null;
                    $email = isset($data->email) ? $data->email : null;
    
                    if (!empty($id) && !empty($name)) {
                        $user = new User();
                        $user->setId($id);
                        $user->setName($name);
                        $user->setEmail($email);
    
                        return $user;
                    }
                }
            }
    
            return null;
        }
    
        protected function getAccessToken($code, $clientId, $clientSecret, $redirectUri)
        {
            if (empty($clientSecret)) {
                throw new RuntimeException('No secret provided');
            }
    
            $url = new Url('https://accounts.google.com/o/oauth2/token');
    
            $params = [
                'code'          => $code,
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'grant_type'    => 'authorization_code'
            ];
    
            $headers = [
                'Accept'     => 'application/json',
                'User-Agent' => Base::getUserAgent()
            ];
    
            $response = $this->httpClient->request(new PostRequest($url, $headers, $params));
    
            if ($response->getStatusCode() == 200) {
                $data = Parser::decode($response->getBody());
                if (isset($data->access_token)) {
                    return $data->access_token;
                }
            }
    
            return null;
        }
    }






