
Ext.define('Fusio.controller.DefaultController', {
    extend: 'Ext.app.Controller',

    init: function(){
        var control = {};
        control[this.getType() + '_detail'] = {
            record_create: this.onRecordCreate,
            record_update: this.onRecordUpdate,
            record_delete: this.onRecordDelete
        };

        control[this.getType()] = {
            show_dialog: this.showDialog
        };

	    this.control(control);
    },

    onLaunch: function(){
        this.loadDefaultStore();
    },

    showDialog: function(type, record){
        Ext.create('Fusio.view.DetailWindow', {
            items: [{
                header: false,
                xtype: this.getType() + '_detail',
                type: type,
                selectedRecord: record
            }]
        }).show();
    },

    onRecordCreate: function(form){
        this.submitForm(form, 'POST');
    },

    onRecordUpdate: function(form){
        this.submitForm(form, 'PUT');
    },

    onRecordDelete: function(form){
        this.submitForm(form, 'DELETE');
    },

    loadDefaultStore: function(){
        var type = this.getType();
        var storeName = type.charAt(0).toUpperCase() + type.slice(1) + 's';
        var store = this.getStore(storeName);
        if (store) {
            store.load();
        }
    },

    submitForm: function(el, method){
        var form = el.getForm();
        form.submit({
            scope: this,
            url: url + 'backend/' + this.getType(),
            headers: {
                'X-HTTP-Method-Override': method
            },
            success: function(form, action){
                //this.getApi().getStore().reload();

                Ext.Msg.alert('Success', action.result.text);
            },
            failure: function(form, action){
                //this.getApi().getStore().reload();

                Ext.Msg.alert('Failed', action.result.text);
            }
        });
    },

    getType: function(){
        console.log('You have to overwrite the getType method');
        return null;
    }

});
