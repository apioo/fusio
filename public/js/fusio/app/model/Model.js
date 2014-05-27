
Ext.define('Fusio.model.Model', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'name', type: 'string' },
        { name: 'table', type: 'string' }
    ]
});
