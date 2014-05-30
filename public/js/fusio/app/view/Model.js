
Ext.define('Fusio.view.Model', {
    extend: 'Fusio.Grid',

    alias: 'widget.model',
    title: 'Model',
    cls: 'fusio-model',

    getDefaultColumns: function(){
        return [{
            text: 'Id',
            dataIndex: 'id',
            width: 80
        },{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
    },

    getDefaultStore: function(){
        return 'Models';
    },

    getDetailPanel: function(record){
        return {
            header: false,
            xtype: 'model_detail',
            selectedRecord: record
        };
    }

});
