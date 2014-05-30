
Ext.define('Fusio.controller.Api', {
    extend: 'Fusio.controller.DefaultController',

    views: ['Api', 'ApiDetail'],
    stores: ['Apis', 'Models', 'Views'],

    getType: function(){
        return 'api';
    }

});
