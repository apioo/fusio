
Ext.define('Fusio.controller.Trigger', {
    extend: 'Fusio.controller.DefaultController',
    requires: 'Fusio.Editor',

    views: ['Trigger', 'TriggerDetail'],
    stores: ['Triggers', 'TriggerTypes', 'Connections'],

    getType: function(){
        return 'trigger';
    }

});
