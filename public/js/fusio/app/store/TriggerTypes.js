
Ext.define('Fusio.store.TriggerTypes', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],
    data: [
        {'key': 'sql-create', 'value': 'Sql-Create'},
        {'key': 'sql-update', 'value': 'Sql-Update'},
        {'key': 'sql-delete', 'value': 'Sql-Delete'},
        {'key': 'mongodb-create', 'value': 'Mongodb-Create'},
        {'key': 'mongodb-update', 'value': 'Mongodb-Update'},
        {'key': 'mongodb-delete', 'value': 'Mongodb-Delete'},
        {'key': 'php-trigger', 'value': 'Php-Trigger'},
        {'key': 'cli-execute', 'value': 'Cli-Execute'},
        {'key': 'http-webhook', 'value': 'Http-WebHook'},
        {'key': 'mq-rabbitmq', 'value': 'MessageQueue-RabbitMQ'},
        {'key': 'mq-beanstalkd', 'value': 'MessageQueue-Beanstalkd'},
        {'key': 'mail-send', 'value': 'Mail-Send'},
        {'key': 'log-request', 'value': 'Log-Request'}
    ]
});
