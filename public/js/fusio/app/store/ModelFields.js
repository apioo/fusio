
Ext.define('Fusio.store.ModelFields', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.ModelField',
    model: 'Fusio.model.ModelField',

    proxy: {
        type: 'ajax',
        url: url + 'backend/model/field?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
