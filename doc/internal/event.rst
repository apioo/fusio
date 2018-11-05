
Event
=====

Fusio has an internal event system which can be used to extend Fusio. This
chapter explains how to use those events and shows which events are available.

Implementation
--------------

To register a new event listener you can use the following code at the
``container.php`` file:

.. code-block:: php
    
    use Fusio\Impl\Event\Action;
    use Fusio\Impl\Event\ActionEvents;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher */
    $eventDispatcher = $container->get('event_dispatcher');
    
    $eventDispatcher->addListener(ActionEvents::CREATE, function(Action\CreatedEvent $event){
    
        // @TODO action was created
    
    });

Reference
---------

**Action-Events** (``Fusio\Impl\Event\ActionEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| action.create            | CREATE              | ``Fusio\Impl\Event\Action\CreatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| action.delete            | DELETE              | ``Fusio\Impl\Event\Action\DeletedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| action.update            | UPDATE              | ``Fusio\Impl\Event\Action\UpdatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+

**App-Events** (``Fusio\Impl\Event\AppEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| app.create               | CREATE              | ``Fusio\Impl\Event\App\CreatedEvent``               |
+--------------------------+---------------------+-----------------------------------------------------+
| app.delete               | DELETE              | ``Fusio\Impl\Event\App\DeletedEvent``               |
+--------------------------+---------------------+-----------------------------------------------------+
| app.generate_token       | GENERATE_TOKEN      | ``Fusio\Impl\Event\App\GeneratedTokenEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+
| app.remove_token         | REMOVE_TOKEN        | ``Fusio\Impl\Event\App\RemovedTokenEvent``          |
+--------------------------+---------------------+-----------------------------------------------------+
| app.update               | UPDATE              | ``Fusio\Impl\Event\App\UpdatedEvent``               |
+--------------------------+---------------------+-----------------------------------------------------+

**Config-Events** (``Fusio\Impl\Event\ConfigEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| config.update            | UPDATE              | ``Fusio\Impl\Event\Config\UpdatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+

**Connection-Events** (``Fusio\Impl\Event\ConnectionEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| connection.create        | CREATE              | ``Fusio\Impl\Event\Connection\CreatedEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+
| connection.delete        | DELETE              | ``Fusio\Impl\Event\Connection\DeletedEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+
| connection.update        | UPDATE              | ``Fusio\Impl\Event\Connection\UpdatedEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+

**Cronjob-Events** (``Fusio\Impl\Event\CronjobEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| cronjob.create           | CREATE              | ``Fusio\Impl\Event\Cronjob\CreatedEvent``           |
+--------------------------+---------------------+-----------------------------------------------------+
| cronjob.delete           | DELETE              | ``Fusio\Impl\Event\Cronjob\DeletedEvent``           |
+--------------------------+---------------------+-----------------------------------------------------+
| cronjob.update           | UPDATE              | ``Fusio\Impl\Event\Cronjob\UpdatedEvent``           |
+--------------------------+---------------------+-----------------------------------------------------+

**Event-Events** (``Fusio\Impl\Event\EventEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| event.create             | CREATE              | ``Fusio\Impl\Event\Event\CreatedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+
| event.delete             | DELETE              | ``Fusio\Impl\Event\Event\DeletedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+
| event.subscribe          | SUBSCRIBE           | ``Fusio\Impl\Event\Event\SubscribedEvent``          |
+--------------------------+---------------------+-----------------------------------------------------+
| event.unsubscribe        | UNSUBSCRIBE         | ``Fusio\Impl\Event\Event\UnsubscribedEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+
| event.update             | UPDATE              | ``Fusio\Impl\Event\Event\UpdatedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+

**Plan-Events** (``Fusio\Impl\Event\PlanEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| plan.create              | CREATE              | ``Fusio\Impl\Event\Plan\CreatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| plan.credit              | CREDIT              | ``Fusio\Impl\Event\Plan\CreditedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+
| plan.delete              | DELETE              | ``Fusio\Impl\Event\Plan\DeletedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| plan.pay                 | PAY                 | ``Fusio\Impl\Event\Plan\PayedEvent``                |
+--------------------------+---------------------+-----------------------------------------------------+
| plan.update              | UPDATE              | ``Fusio\Impl\Event\Plan\UpdatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+

**Rate-Events** (``Fusio\Impl\Event\RateEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| rate.create              | CREATE              | ``Fusio\Impl\Event\Rate\CreatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| rate.delete              | DELETE              | ``Fusio\Impl\Event\Rate\DeletedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| rate.update              | UPDATE              | ``Fusio\Impl\Event\Rate\UpdatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+

**Routes-Events** (``Fusio\Impl\Event\RoutesEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| routes.create            | CREATE              | ``Fusio\Impl\Event\Routes\CreatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| routes.delete            | DELETE              | ``Fusio\Impl\Event\Routes\DeletedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| routes.deploy            | DEPLOY              | ``Fusio\Impl\Event\Routes\DeployedEvent``           |
+--------------------------+---------------------+-----------------------------------------------------+
| routes.update            | UPDATE              | ``Fusio\Impl\Event\Routes\UpdatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+

**Schema-Events** (``Fusio\Impl\Event\SchemaEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| schema.create            | CREATE              | ``Fusio\Impl\Event\Schema\CreatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| schema.delete            | DELETE              | ``Fusio\Impl\Event\Schema\DeletedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+
| schema.update            | UPDATE              | ``Fusio\Impl\Event\Schema\UpdatedEvent``            |
+--------------------------+---------------------+-----------------------------------------------------+

**Scope-Events** (``Fusio\Impl\Event\ScopeEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| scope.create             | CREATE              | ``Fusio\Impl\Event\Scope\CreatedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+
| scope.delete             | DELETE              | ``Fusio\Impl\Event\Scope\DeletedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+
| scope.update             | UPDATE              | ``Fusio\Impl\Event\Scope\UpdatedEvent``             |
+--------------------------+---------------------+-----------------------------------------------------+

**Transaction-Events** (``Fusio\Impl\Event\TransactionEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| transaction.execute      | EXECUTE             | ``Fusio\Impl\Event\Transaction\ExecutedEvent``      |
+--------------------------+---------------------+-----------------------------------------------------+
| transaction.prepare      | PREPARE             | ``Fusio\Impl\Event\Transaction\PreparedEvent``      |
+--------------------------+---------------------+-----------------------------------------------------+

**User-Events** (``Fusio\Impl\Event\UserEvents``)

+--------------------------+---------------------+-----------------------------------------------------+
| user.change_password     | CHANGE_PASSWORD     | ``Fusio\Impl\Event\User\ChangedPasswordEvent``      |
+--------------------------+---------------------+-----------------------------------------------------+
| user.change_status       | CHANGE_STATUS       | ``Fusio\Impl\Event\User\ChangedStatusEvent``        |
+--------------------------+---------------------+-----------------------------------------------------+
| user.create              | CREATE              | ``Fusio\Impl\Event\User\CreatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| user.delete              | DELETE              | ``Fusio\Impl\Event\User\DeletedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+
| user.fail_authentication | FAIL_AUTHENTICATION | ``Fusio\Impl\Event\User\FailedAuthenticationEvent`` |
+--------------------------+---------------------+-----------------------------------------------------+
| user.update              | UPDATE              | ``Fusio\Impl\Event\User\UpdatedEvent``              |
+--------------------------+---------------------+-----------------------------------------------------+

