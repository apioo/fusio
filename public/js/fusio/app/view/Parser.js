
Ext.define('Fusio.view.Parser', {
    extend: 'Fusio.Grid',

    alias: 'widget.parser',
    title: 'Parser',
    cls: 'fusio-parser',

    getDefaultColumns: function(){
        return [{
            text: 'Id',
            dataIndex: 'id',
            width: 80
        },{
            text: 'Type',
            dataIndex: 'type',
            width: 300
        },{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
    },

    getDefaultStore: function(){
        return 'Parsers';
    }

});
