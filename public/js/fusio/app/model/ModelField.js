
Ext.define('Fusio.model.ModelField', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'name', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'required', type: 'bool' },
        { name: 'length', type: 'integer' },
        { name: 'reference', type: 'integer' }
    ]
});
