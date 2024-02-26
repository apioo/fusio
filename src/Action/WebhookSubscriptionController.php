<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Faker\Factory;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

class WebhookSubscriptionController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $db = $this->connector->getConnection('System');
        $app_id = $context->getApp()->getId();
        $me = $context->getUser()->getId();

        $myapp = $db->executeQuery("SELECT id FROM fusio_app WHERE id = :app_id AND user_id = :user_id", [
            'app_id' => $app_id,
            'user_id' => $me
        ])->fetchAssociative();
        if(!$myapp) {
            return $this->response->build(403, [], [
                'message' => "Sorry, you have not access to this app"
            ]);
        }

        $faker = Factory::create();
        $salt = $faker->regexify('.{30}');

        $db->executeStatement("INSERT INTO danapay_app_configurations (`app_id`, `description`, `redirection_url`, `webhook`, `salt`, `created_at`, `updated_at`) 
            VALUES (:app_id, 'My beautiful app', :redirection_url, :webhook, :salt, NOW(), NOW()) ON DUPLICATE KEY UPDATE `webhook` = :webhook, `salt` = :salt, `updated_at` = NOW()", [
                'app_id' => $app_id,
                'salt' => $salt,
                'redirection_url' => $context->getApp()->getUrl(),
                'webhook' => $request->get('callback_url')
            ]);

        return $this->response->build(200, [], [
            'message' => 'successfully updated. Please write down the following salt, it will be displayed only once.',
            'salt' => $salt
        ]);
    }
}
