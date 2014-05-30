
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
                cls: 'fusio-grid-button-create',
                text: 'Create',
                handler: this.showCreateDialog,
                scope: this
            },{
                xtype: 'button',
                cls: 'fusio-grid-button-update',
                text: 'Update',
                disabled: true,
                handler: this.showUpdateDialog,
                scope: this
            },{
                xtype: 'button',
                cls: 'fusio-grid-button-delete',
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

        this.listeners = {
            scope: this,
            itemclick: function(){
                var buttons = this.query('button[cls~=fusio-grid-button-update]');
                if (buttons) {
                    buttons[0].enable();
                }

                buttons = this.query('button[cls~=fusio-grid-button-delete]');
                if (buttons) {
                    buttons[0].enable();
                }
            }
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

    showDetailWindow: function(type, record){
        this.fireEvent('show_dialog', type, record);
    },

    showCreateDialog: function(){
        this.showDetailWindow('create', null);
    },

    showUpdateDialog: function(){
        this.showDetailWindow('update', this.getSelectedRecord());
    },

    showDeleteDialog: function(){
        this.showDetailWindow('delete', this.getSelectedRecord());
    }

});
