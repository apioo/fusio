
Ext.define('Fusio.view.ModelDetail', {
    extend: 'Fusio.DetailPanel',

    alias: 'widget.model_detail',

    getDefaultItems: function(){
        var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
            clicksToMoveEditor: 1,
            autoCancel: false,
            errorSummary: false
        });

        var grid = Ext.create('Ext.grid.Panel', {
            store: 'ModelFields',
            height: 480,
            columns: [{ 
                text: 'Name',
                dataIndex: 'name',
                flex: 1,
                editor: {
                    allowBlank: false
                }
            },{ 
                text: 'Type', 
                dataIndex: 'type',
                editor: new Ext.form.field.ComboBox({
                    typeAhead: true,
                    triggerAction: 'all',
                    store: [
                        ['string', 'string'],
                        ['integer', 'integer'],
                        ['float', 'float'],
                        ['boolean', 'boolean'],
                        ['array', 'array'],
                        ['object', 'object']
                    ]
                })
            },{ 
                xtype: 'checkcolumn',
                text: 'Required', 
                dataIndex: 'required' 
            },{ 
                text: 'Reference', 
                dataIndex: 'reference',
                editor: new Ext.form.field.ComboBox({
                    typeAhead: true,
                    triggerAction: 'all',
                    displayField: 'name',
                    valueField: 'id',
                    store: Ext.data.StoreManager.lookup('Models')
                })
            },{ 
                xtype: 'numbercolumn',
                format: '0',
                text: 'Length', 
                dataIndex: 'length',
                editor: {
                    xtype: 'numberfield',
                    allowBlank: true,
                    minValue: 0,
                    maxValue: 65535
                }
            }],
            tbar: [{
                text: 'Add field',
                cls: 'add-field',
                handler: function() {
                    rowEditing.cancelEdit();

                    var record = Ext.create('Fusio.model.ModelField', {
                        name: '',
                        type: 'string',
                        required: true,
                        length: null
                    });

                    var store = Ext.data.StoreManager.lookup('ModelFields');
                    store.add(record);

                    //rowEditing.startEdit(0, 0);
                }
            }, {
                text: 'Remove field',
                cls: 'remove-field',
                handler: function() {
                    rowEditing.cancelEdit();

                    var store = Ext.data.StoreManager.lookup('ModelFields');
                    store.remove(sm.getSelection());

                    var sm = grid.getSelectionModel();
                    if (store.getCount() > 0) {
                        sm.select(0);
                    }
                }
            }],
            plugins: [rowEditing]
        });

        return [{
            fieldLabel: 'Name',
            name: 'name',
            allowBlank: false
        }, grid];
    }

});
