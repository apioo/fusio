
Ext.define('Fusio.store.Connections', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.Connection',
    model: 'Fusio.model.Connection',

    proxy: {
        type: 'ajax',
        url: url + 'backend/connection?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
