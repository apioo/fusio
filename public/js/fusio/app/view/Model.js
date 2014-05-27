
Ext.define('Fusio.view.Model', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.model',
    store: 'Models',

    title: 'Model',
    border: false,
    cls: 'fusio-model',

    initComponent: function() {
        this.columns = [{
            text: 'Id',
            dataIndex: 'id',
            width: 80
        },{
            text: 'Name',
            dataIndex: 'name',
            flex: 1
        }];
        
        this.dockedItems = [{
            dock: 'top',
            xtype: 'toolbar',
            items: [{
                xtype: 'button',
                text: 'Create',
                handler: this.showCreateDialog,
                scope: this
            },{
                xtype: 'button',
                text: 'Update',
                handler: this.showUpdateDialog,
                scope: this
            },{
                xtype: 'button',
                text: 'Delete'
            }]
        }];

        this.bbar = {
            xtype: 'pagingtoolbar',
            pageSize: 16,
            store: 'Models',
            displayInfo: true
        };

        this.callParent();
    },

    showCreateDialog: function(){

        var win = Ext.create('Ext.window.Window', {
            title: 'Model fields',
            height: 600,
            width: 800,
            modal: true,
            layout: 'fit',
            resizeable: false,
            items: [{
                header: false,
                xtype: 'model_detail'
            }]
        });

        win.show();

    },

    showUpdateDialog: function(){


    }

});
