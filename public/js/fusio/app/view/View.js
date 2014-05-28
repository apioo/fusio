
Ext.define('Fusio.view.View', {
    extend: 'Fusio.Grid',

    alias: 'widget.view',
    title: 'View',
    cls: 'fusio-view',

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
        return 'Views';
    },

    getDetailPanel: function(){
        return {
            header: false,
            xtype: 'view_detail',
            selected_record: this.getSelectedRecord()
        };
    }

});
