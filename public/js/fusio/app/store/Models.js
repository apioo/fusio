
Ext.define('Fusio.store.Models', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.Model',
    model: 'Fusio.model.Model',

    proxy: {
        type: 'ajax',
        url: url + 'backend/model?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
