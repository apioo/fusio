
Ext.define('Fusio.view.TriggerDetail', {
    extend: 'Fusio.DetailPanel',

    alias: 'widget.parser_detail',

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
            value: 'sql-query',
            store: 'TriggerTypes',
            forceSelection: true,
            editable: false,
            listeners: {
                scope: this,
                select: function(el){
                    var parameters = this.query('fieldcontainer[cls~=fusio-parser-detail-parameters]');
                    if (parameters) {
                        var parameter = parameters[0];
                        var params;
                        if (el.getValue() == 'sql-query') {
                            params = this.getSqlQueryParameters();
                        } else if (el.getValue() == 'mongodb-create') {
                            params = this.getMongodbCreateParameters();
                        } else if (el.getValue() == 'mongodb-update') {
                            params = this.getMongodbUpdateParameters();
                        } else if (el.getValue() == 'mongodb-delete') {
                            params = this.getMongodbDeleteParameters();
                        } else if (el.getValue() == 'php-trigger') {
                            params = this.getPhpTriggerParameters();
                        } else if (el.getValue() == 'cli-execute') {
                            params = this.getCliExecuteParameters();
                        } else if (el.getValue() == 'http-webhook') {
                            params = this.getHttpWebhookParameters();
                        } else if (el.getValue() == 'mq-rabbitmq') {
                            params = this.getMqRabbitMqParameters();
                        } else if (el.getValue() == 'mq-beanstalkd') {
                            params = this.getMqBeanstalkdParameters();
                        } else if (el.getValue() == 'mq-ironmq') {
                            params = this.getMqIronMqParameters();
                        } else if (el.getValue() == 'search-elasticsearch') {
                            params = this.getSearchElasticSearchParameters();
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
            cls: 'fusio-parser-detail-parameters',
            defaults: {
                width: 770
            },
            items: this.getSqlQueryParameters()
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

    getMongodbCreateParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Collection',
            name: 'collection',
            allowBlank: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Document',
            name: 'document',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 371
        }];
    },

    getMongodbUpdateParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Collection',
            name: 'collection',
            allowBlank: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Criteria',
            name: 'criteria',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 100
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Document',
            name: 'document',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 266
        }];
    },

    getMongodbDeleteParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Collection',
            name: 'collection',
            allowBlank: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Criteria',
            name: 'criteria',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 200
        }];
    },

    getPhpTriggerParameters: function(){
        return [{
            xtype: 'textfield',
            fieldLabel: 'Class',
            name: 'class',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: 'Arguments',
            name: 'arguments',
            allowBlank: false
        }];
    },

    getCliExecuteParameters: function(){
        return [{
            xtype: 'textfield',
            fieldLabel: 'Command',
            name: 'command',
            allowBlank: false
        }];
    },

    getHttpWebhookParameters: function(){
        return [{
            xtype: 'textfield',
            fieldLabel: 'Url',
            name: 'url',
            allowBlank: false
        }];
    },

    getMqRabbitMqParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Exchange name',
            name: 'exchange_name',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: 'Routing key',
            name: 'routing_key',
            allowBlank: false
        }];
    },

    getMqBeanstalkdParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Queue',
            name: 'queue',
            allowBlank: false
        }];
    },

    getMqIronMqParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Queue',
            name: 'queue',
            allowBlank: false
        }];
    },

    getSearchElasticSearchParameters: function(){
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
            xtype: 'textfield',
            fieldLabel: 'Index',
            name: 'index',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: 'Type',
            name: 'type',
            allowBlank: false
        },{
            xtype: 'textfield',
            fieldLabel: 'Id',
            name: 'id',
            allowBlank: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Document',
            name: 'document',
            allowBlank: false,
            mode: 'json',
            width: 665,
            height: 313
        }];
    }

});
