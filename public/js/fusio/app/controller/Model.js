
Ext.define('Fusio.controller.Model', {
    extend: 'Fusio.controller.DefaultController',

    views: ['Model', 'ModelDetail'],
    stores: ['Models', 'ModelFields'],

    getType: function(){
        return 'model';
    }

});
