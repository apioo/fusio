
Ext.define('Fusio.view.Trigger', {
    extend: 'Fusio.Grid',

    alias: 'widget.trigger',
    title: 'Trigger',
    cls: 'fusio-trigger',

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
        return 'Triggers';
    },

    getDetailPanel: function(type, record){
        return {
            header: false,
            xtype: 'trigger_detail',
            type: type,
            selectedRecord: record
        };
    }

});
