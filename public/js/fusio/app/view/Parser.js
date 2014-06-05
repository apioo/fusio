
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
    },

    getDetailPanel: function(type, record){
        return {
            header: false,
            xtype: 'parser_detail',
            type: type,
            selectedRecord: record
        };
    }

});
