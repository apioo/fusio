
Ext.define('Fusio.store.ViewTypes', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],
    data: [
        {'key': 'sql-query', 'value': 'Sql-Query'},
        {'key': 'mongodb-query', 'value': 'Mongodb-Query'},
        {'key': 'custom-response', 'value': 'Custom-Response'},
        {'key': 'cached-response', 'value': 'Cached-Response'}
    ]
});
