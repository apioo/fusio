
Ext.define('Fusio.Grid', {
    extend: 'Ext.grid.Panel',

    alias: 'widget.fusio_grid',

    title: 'Grid',
    border: false,
    cls: 'fusio-grid',

    initComponent: function() {
        this.columns = this.getDefaultColumns();
        this.store = this.getDefaultStore(),

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
                disabled: true,
                handler: this.showUpdateDialog,
                scope: this
            },{
                xtype: 'button',
                text: 'Delete',
                disabled: true,
                handler: this.showDeleteDialog,
                scope: this
            }]
        }];

        this.bbar = {
            xtype: 'pagingtoolbar',
            pageSize: 16,
            store: this.getDefaultStore(),
            displayInfo: true
        };

        this.callParent();
    },

    getDefaultColumns: function(){
        console.log('You have to overwrite the getDefaultColumns method');
        return null;
    },

    getDefaultStore: function(){
        console.log('You have to overwrite the getDefaultStore method');
        return null;
    },

    getDetailPanel: function(){
        console.log('You have to overwrite the getDetailPanel method');
        return null;
    },

    getSelectedRecord: function(){
        return this.getSelectionModel().getLastSelected();
    },

    showDetailWindow: function(){
        Ext.create('Ext.window.Window', {
            title: 'Details',
            height: 600,
            width: 800,
            modal: true,
            layout: 'fit',
            resizeable: false,
            items: [this.getDetailPanel()]
        }).show();
    },

    showCreateDialog: function(){
        this.showDetailWindow();
    },

    showUpdateDialog: function(){
        this.showDetailWindow();
    },

    showDeleteDialog: function(){
        this.showDetailWindow();
    }

});
