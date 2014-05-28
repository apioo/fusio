
Ext.define('Fusio.model.Api', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'status', type: 'string' },
        { name: 'path', type: 'string' },
        { name: 'description', type: 'string' }
    ]
});
