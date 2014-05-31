
Ext.define('Fusio.model.Api', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'status', type: 'integer' },
        { name: 'path', type: 'string' },
        { name: 'methodGet', type: 'boolean' },
        { name: 'methodPost', type: 'boolean' },
        { name: 'methodPut', type: 'boolean' },
        { name: 'methodDelete', type: 'boolean' },
        { name: 'description', type: 'string' },
        { name: 'modelId', type: 'integer' },
        { name: 'modelName', type: 'string' },
        { name: 'viewId', type: 'integer' },
        { name: 'viewName', type: 'string' }
    ]
});
