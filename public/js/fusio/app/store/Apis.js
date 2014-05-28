
Ext.define('Fusio.store.Apis', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.Api',
    model: 'Fusio.model.Api',

    proxy: {
        type: 'ajax',
        url: url + 'backend/api?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
