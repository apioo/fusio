
Ext.define('Fusio.view.ApiDetail', {
    extend: 'Fusio.DetailPanel',

    alias: 'widget.view_detail',

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
            store: 'ViewTypes',
            forceSelection: true,
            editable: false,
            listeners: {
                scope: this,
                select: function(el){
                    var parameters = this.query('fieldcontainer[cls~=fusio-view-detail-parameters]');
                    if (parameters) {
                        var parameter = parameters[0];
                        var params;
                        if (el.getValue() == 'mysql-query') {
                            params = this.getMysqlQueryParameters();
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
            //fieldLabel: 'Parameters',
            cls: 'fusio-view-detail-parameters',
            items: []
        }];
    },

    getMysqlQueryParameters: function(){
        return [{
            xtype: 'textfield',
            fieldLabel: 'Connection',
            name: 'connection',
            allowBlank: false
        },{
            xtype: 'aceeditor',
            fieldLabel: 'Query',
            name: 'query',
            allowBlank: false,
            width: 660,
            height: 200
        }];
    }

});
