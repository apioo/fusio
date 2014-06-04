
Ext.define('Fusio.store.ContentTypes', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],
    data: [
        {'key': 'application/atom+xml', 'value': 'application/atom+xml'},
        {'key': 'application/json', 'value': 'application/json'},
        {'key': 'application/rss+xml', 'value': 'application/rss+xml'},
        {'key': 'application/xml', 'value': 'application/xml'},
        {'key': 'text/html', 'value': 'text/html'},
        {'key': 'text/plain', 'value': 'text/plain'}
    ]
});
