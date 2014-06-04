
Ext.define('Fusio.store.Parsers', {
    extend: 'Ext.data.Store',
    requires: 'Fusio.model.Parser',
    model: 'Fusio.model.Parser',

    proxy: {
        type: 'ajax',
        url: url + 'backend/parser?format=json',
        reader: {
            type: 'json',
            root: 'entry'
        }
    }
});
