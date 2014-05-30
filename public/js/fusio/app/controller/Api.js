
Ext.define('Fusio.controller.Api', {
    extend: 'Ext.app.Controller',

    views: ['Api', 'ApiDetail'],
    stores: ['Apis', 'Views'],
    refs: [{
        selector: 'panel[cls~=fusio-api]',
        ref: 'api'
    }],

    init: function(){
	    this.control({
	        'api_detail': {
                record_create: this.onRecordCreate,
                record_update: this.onRecordUpdate,
	            record_delete: this.onRecordDelete
	        }
	    });

        this.getApisStore().load();
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

    submitForm: function(el, method){
        var form = el.getForm();
        form.submit({
            scope: this,
            url: url + 'backend/api',
            headers: {
                'X-HTTP-Method-Override': method
            },
            success: function(form, action){
                this.getApi().getStore().reload();

                Ext.Msg.alert('Success', action.result.text);
            },
            failure: function(form, action){
                this.getApi().getStore().reload();

                Ext.Msg.alert('Failed', action.result.text);
            }
        });
    }

});
