
Ext.define('Fusio.view.ApiDetail', {
    extend: 'Fusio.DetailPanel',

    alias: 'widget.api_detail',

    getDefaultItems: function(){
        var statusStore = Ext.create('Ext.data.Store', {
            fields: ['key', 'value'],
            data: [
                {'key': 1, 'value': 'Development'},
                {'key': 2, 'value': 'Live'},
                {'key': 3, 'value': 'Deprecated'},
                {'key': 4, 'value': 'Removed'}
            ]
        });

        statusStore.load();
        Ext.data.StoreManager.lookup('Models').load();
        Ext.data.StoreManager.lookup('Views').load();

        return [{
            xtype: 'hiddenfield',
            name: 'id',
            value: this.selectedRecord ? this.selectedRecord.get('id') : null
        },{
            xtype: 'combo',
            fieldLabel: 'Status',
            name: 'status',
            displayField: 'value',
            valueField: 'key',
            store: statusStore,
            forceSelection: true,
            editable: false,
            value: this.selectedRecord ? this.selectedRecord.get('status') : null
        },{
            fieldLabel: 'Path',
            name: 'path',
            allowBlank: false,
            emptyText: '/v1/acme/store',
            value: this.selectedRecord ? this.selectedRecord.get('path') : null
        },{
            xtype: 'textareafield',
            fieldLabel: 'Description',
            name: 'description',
            value: this.selectedRecord ? this.selectedRecord.get('description') : null
        },{
            xtype: 'fieldcontainer',
            fieldLabel: 'GET',
            layout: 'hbox',
            items: [{
                xtype: 'checkbox',
                name: 'get',
                margins: '0 6 0 0'
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Request parser',
                name: 'model',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Models'),
                editable: false,
                disabled: true,
                value: this.selectedRecord ? this.selectedRecord.get('modelId') : null
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Response view',
                margins: '0 0 0 6',
                name: 'view',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Views'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('viewId') : null
            }]
        },{
            xtype: 'fieldcontainer',
            fieldLabel: 'POST',
            layout: 'hbox',
            items: [{
                xtype: 'checkbox',
                name: 'get',
                margins: '0 6 0 0'
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Request parser',
                name: 'model',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Models'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('modelId') : null
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Response view',
                margins: '0 0 0 6',
                name: 'view',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Views'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('viewId') : null
            }]
        },{
            xtype: 'fieldcontainer',
            fieldLabel: 'PUT',
            layout: 'hbox',
            items: [{
                xtype: 'checkbox',
                name: 'get',
                margins: '0 6 0 0'
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Request parser',
                name: 'model',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Models'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('modelId') : null
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Response view',
                margins: '0 0 0 6',
                name: 'view',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Views'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('viewId') : null
            }]
        },{
            xtype: 'fieldcontainer',
            fieldLabel: 'DELETE',
            layout: 'hbox',
            items: [{
                xtype: 'checkbox',
                name: 'get',
                margins: '0 6 0 0'
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Request parser',
                name: 'model',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Models'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('modelId') : null
            },{
                xtype: 'combo',
                flex: 2,
                emptyText: 'Response view',
                margins: '0 0 0 6',
                name: 'view',
                displayField: 'name',
                valueField: 'id',
                store: Ext.data.StoreManager.lookup('Views'),
                editable: false,
                value: this.selectedRecord ? this.selectedRecord.get('viewId') : null
            }]
        }];
    }

});
