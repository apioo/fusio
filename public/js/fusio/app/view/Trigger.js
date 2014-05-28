
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
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
    },

    getDefaultStore: function(){
        return 'Triggers';
    },

    getDetailPanel: function(){
        return {
            header: false,
            xtype: 'trigger_detail',
            selected_record: this.getSelectedRecord()
        };
    }

});
