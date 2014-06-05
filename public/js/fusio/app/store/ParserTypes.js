
Ext.define('Fusio.store.ParserTypes', {
    extend: 'Ext.data.Store',

    fields: ['key', 'value'],
    data: [
        {'key': 'generic-format', 'value': 'Generic-Format'},
        {'key': 'xml-dom', 'value': 'XML-DOM'},
        {'key': 'php-parser', 'value': 'Php-Parser'}
    ]
});
