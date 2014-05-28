
Ext.define('Fusio.store.Views', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.View',
    model: 'Fusio.model.View',

    proxy: {
        type: 'ajax',
        url: url + 'backend/view?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
