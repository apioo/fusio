
Ext.define('Fusio.view.Api', {
    extend: 'Fusio.Grid',

    alias: 'widget.api',
    title: 'Api',
    cls: 'fusio-api',

    getDefaultColumns: function(){
        return [{
            text: 'Id',
            dataIndex: 'id',
            width: 80
        },{
            text: 'Path',
            dataIndex: 'path',
            width: 300
        },{
            text: 'Description',
            dataIndex: 'description',
            flex: 1
        }];
    },

    getDefaultStore: function(){
        return 'Apis';
    },

    getDetailPanel: function(type, record){
        return {
            header: false,
            xtype: 'api_detail',
            type: type,
            selectedRecord: record
        };
    }

});
