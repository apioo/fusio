
Ext.define('Fusio.view.TriggerDetail', {
    extend: 'Fusio.DetailPanel',

    alias: 'widget.trigger_detail',

    getDefaultItems: function(){
        return [{
            xtype: 'hiddenfield',
            name: 'id',
            value: this.selectedRecord ? this.selectedRecord.get('id') : null
        },{
            xtype: 'combo',
            fieldLabel: 'Type',
            name: 'type',
            displayField: 'value',
            valueField: 'key',
            value: 'sql-create',
            store: 'TriggerTypes',
            forceSelection: true,
            editable: false,
            listeners: {
                scope: this,
                select: function(el){
                    var parameters = this.query('fieldcontainer[cls~=fusio-trigger-detail-parameters]');
                    if (parameters) {
                        var parameter = parameters[0];
                        var params;
                        if (el.getValue() == 'sql-query') {
                            params = this.getSqlQueryParameters();
                        } else if (el.getValue() == 'mongodb-query') {
                            params = this.getMongodbQueryParameters();
                        } else if (el.getValue() == 'custom-response') {
                            params = this.getCustomResponseParameters();
                        } else if (el.getValue() == 'cached-response') {
                            params = this.getCachedResponseParameters();
                        }

                        if (params) {
                            parameter.removeAll();
                            for (var i = 0; i < params.length; i++) {
                                parameter.add(params[i]);
                            }
                        }
                    }
                }
            }
        },{
            fieldLabel: 'Name',
            name: 'name',
            allowBlank: false,
            value: this.selectedRecord ? this.selectedRecord.get('name') : null
        },{
            xtype: 'fieldcontainer',
            cls: 'fusio-trigger-detail-parameters',
            defaults: {
                width: 770
            },
            items: this.getSqlCreateParameters()
        }];
    },

    getSqlQueryParameters: function(){
        return [{
            xtype: 'combo',
            fieldLabel: 'Connection',
            name: 'connection',
            displayField: 'name',
            valueField: 'id',
            store: 'Connections',
            forceSelection: true,
            editable: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Query',
            name: 'query',
            allowBlank: false,
            mode: 'sql',
            width: 665,
            height: 400
        }];
    },

    getMongodbQueryParameters: function(){
        return [{
            xtype: 'combo',
            fieldLabel: 'Connection',
            name: 'connection',
            displayField: 'name',
            valueField: 'id',
            store: 'Connections',
            forceSelection: true,
            editable: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Query',
            name: 'query',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 200
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Fields',
            name: 'fields',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 195
        }];
    },

    getCustomResponseParameters: function(){
        return [{
            xtype: 'combo',
            fieldLabel: 'Content-Type',
            name: 'content_type',
            displayField: 'value',
            valueField: 'key',
            value: 'application/json',
            store: 'ContentTypes',
            forceSelection: true,
            editable: false
        },{
            xtype: 'combo',
            fieldLabel: 'View',
            name: 'view',
            displayField: 'name',
            valueField: 'id',
            store: 'Views',
            forceSelection: true,
            editable: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Template',
            name: 'template',
            allowBlank: false,
            mode: 'text',
            width: 665,
            height: 371
        }];
    },

    getCachedResponseParameters: function(){
        return [{
            xtype: 'combo',
            fieldLabel: 'View',
            name: 'view',
            displayField: 'name',
            valueField: 'id',
            store: 'Views',
            forceSelection: true,
            editable: false
        },{
            xtype: 'numberfield',
            fieldLabel: 'Max-Age',
            name: 'name',
            allowBlank: false,
            value: 2,
            minValue: 0,
            maxValue: 8765
        }];
    }

});
