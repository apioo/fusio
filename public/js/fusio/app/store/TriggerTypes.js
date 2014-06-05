
Ext.define('Fusio.store.TriggerTypes', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],
    data: [
        {'key': 'sql-query', 'value': 'Sql-Query'},
        {'key': 'mongodb-create', 'value': 'Mongodb-Create'},
        {'key': 'mongodb-update', 'value': 'Mongodb-Update'},
        {'key': 'mongodb-delete', 'value': 'Mongodb-Delete'},
        {'key': 'php-trigger', 'value': 'Php-Trigger'},
        {'key': 'cli-execute', 'value': 'Cli-Execute'},
        {'key': 'http-webhook', 'value': 'Http-WebHook'},
        {'key': 'mq-rabbitmq', 'value': 'MessageQueue-RabbitMQ'},
        {'key': 'mq-beanstalkd', 'value': 'MessageQueue-Beanstalkd'},
        {'key': 'mq-ironmq', 'value': 'MessageQueue-IronMQ'},
        {'key': 'search-elasticsearch', 'value': 'Search-ElasticSearch'}
    ]
});
