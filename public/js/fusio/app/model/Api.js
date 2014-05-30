
Ext.define('Fusio.model.Api', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'status', type: 'integer' },
        { name: 'path', type: 'string' },
        { name: 'description', type: 'string' },
        { name: 'modelId', type: 'integer' },
        { name: 'modelName', type: 'string' },
        { name: 'viewId', type: 'integer' },
        { name: 'viewName', type: 'string' }
    ]
});
