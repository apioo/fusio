
Ext.define('Fusio.controller.Model', {
    extend: 'Ext.app.Controller',

    views: ['Model', 'ModelDetail'],
    stores: ['Models', 'ModelFields'],

    init: function() {
        this.getModelsStore().load();
    }

});
