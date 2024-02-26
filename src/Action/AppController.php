<?php
namespace App\Action;

use Doctrine\DBAL\DriverManager;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Model\UserInterface;

class AppController extends ActionAbstract
{
    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed {
        $db = $this->connector->getConnection('System');
        $app_id = $request->get('id');
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

        if($request->getContext()->getRequest()->getMethod()=='GET') {
            $row = $db->executeQuery("SELECT `description`, `redirection_url`, `webhook` FROM danapay_app_configurations WHERE app_id = ?", [$app_id])->fetchAssociative();
            return $this->response->build(200, [], $row);
        }
        $db->executeStatement("INSERT INTO danapay_app_configurations (`app_id`, `description`, `redirection_url`, `webhook`, `created_at`, `updated_at`) 
            VALUES (:app_id, :description, :redirection_url, :webhook, NOW(), NOW()) ON DUPLICATE KEY UPDATE `description` = :description, `redirection_url` = :redirection_url, `webhook` = :webhook, `updated_at` = NOW()", [
                'app_id' => $app_id,
                'description' => $request->getPayload()->get('description'),
                'redirection_url' => $request->getPayload()->get('redirection_url'),
                'webhook' => $request->getPayload()->get('webhook')
            ]);

        return $this->response->build(200, [], [
            'message' => 'successfully updated'
        ]);
    }
}