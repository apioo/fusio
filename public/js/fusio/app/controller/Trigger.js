
Ext.define('Fusio.controller.Trigger', {
    extend: 'Fusio.controller.DefaultController',

    views: ['Trigger', 'TriggerDetail'],
    stores: ['Triggers'],

    getType: function(){
        return 'trigger';
    }

});
