
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
            fieldLabel: 'Methods',
            xtype: 'checkboxgroup',
            columns: [80, 80, 80, 80],
            items: [{
                boxLabel: 'GET',
                name: 'methodGet',
                inputValue: '1',
                checked: this.selectedRecord ? this.selectedRecord.get('methodGet') : true
            }, {
                boxLabel: 'POST',
                name: 'methodPost',
                inputValue: '1',
                checked: this.selectedRecord ? this.selectedRecord.get('methodPost') : true
            }, {
                boxLabel: 'PUT',
                name: 'methodPut',
                inputValue: '1',
                checked: this.selectedRecord ? this.selectedRecord.get('methodPut') : true
            }, {
                boxLabel: 'DELETE',
                name: 'methodDelete',
                inputValue: '1',
                checked: this.selectedRecord ? this.selectedRecord.get('methodDelete') : true
            }]
        },{
            fieldLabel: 'Description',
            name: 'description',
            value: this.selectedRecord ? this.selectedRecord.get('description') : null
        },{
            xtype: 'combo',
            fieldLabel: 'Model',
            name: 'model',
            displayField: 'name',
            valueField: 'id',
            store: Ext.data.StoreManager.lookup('Models'),
            forceSelection: true,
            editable: false,
            value: this.selectedRecord ? this.selectedRecord.get('modelId') : null
        },{
            xtype: 'combo',
            fieldLabel: 'View',
            name: 'view',
            displayField: 'name',
            valueField: 'id',
            store: Ext.data.StoreManager.lookup('Views'),
            forceSelection: true,
            editable: false,
            value: this.selectedRecord ? this.selectedRecord.get('viewId') : null
        }];
    }

});
