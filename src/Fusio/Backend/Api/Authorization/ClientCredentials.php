<?php

namespace Fusio\Backend\Api\Authorization;

use Doctrine\DBAL\Connection;
use Hautelook\Phpass\PasswordHash;
use PSX\Oauth2\Provider\GrantType\ClientCredentialsAbstract;
use PSX\Oauth2\Provider\Credentials;
use PSX\Oauth2\AccessToken;
use PSX\Oauth2\Authorization\Exception\ServerErrorException;

class ClientCredentials extends ClientCredentialsAbstract
{
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	protected function generate(Credentials $credentials, $scope)
	{
		$sql = 'SELECT id, 
				       name, 
				       password
			      FROM fusio_user
			     WHERE status = :status
			       AND name = :name';

		$user = $this->connection->fetchAssoc($sql, array(
			'status' => 1,
			'name'   => $credentials->getClientId(),
		));

		if(!empty($user))
		{
			if(password_verify($credentials->getClientSecret(), $user['password']))
			{
				// generate access token
				$accessToken = hash('sha256', uniqid());

				$sql = 'INSERT INTO fusio_app_token
								SET appId = :app_id, 
								    userId = :user_id, 
								    token = :token, 
								    scope = :scope, 
								    ip = :ip, 
								    expire = :expire, 
								    date = NOW()';

				$expires = new \DateTime();
				$expires->add(new \DateInterval('PT1H'));

				$this->connection->executeUpdate($sql, array(
					'app_id'  => 1,
					'user_id' => $user['id'],
					'token'   => $accessToken,
					'scope'   => 'backend',
					'ip'      => $_SERVER['REMOTE_ADDR'],
					'expire' => $expires->getTimestamp(),
				));

				$token = new AccessToken();
				$token->setAccessToken($accessToken);
				$token->setTokenType('bearer');
				$token->setExpiresIn($expires->getTimestamp());
				$token->setScope('backend');

				return $token;
			}
			else
			{
				throw new ServerErrorException('Invalid password');
			}
		}
		else
		{
			throw new ServerErrorException('Unknown user');
		}
	}
}
