
Ext.define('Fusio.controller.View', {
    extend: 'Fusio.controller.DefaultController',
    requires: 'Fusio.Editor',

    views: ['View', 'ViewDetail'],
    stores: ['Views', 'ViewTypes', 'Connections', 'ContentTypes'],

    getType: function(){
        return 'view';
    }

});
