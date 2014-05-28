
Ext.define('Fusio.store.Triggers', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.Trigger',
    model: 'Fusio.model.Trigger',

    proxy: {
        type: 'ajax',
        url: url + 'backend/trigger?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
