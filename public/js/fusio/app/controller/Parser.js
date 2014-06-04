
Ext.define('Fusio.controller.Parser', {
    extend: 'Fusio.controller.DefaultController',
    requires: 'Fusio.Editor',

    views: ['Parser', 'ParserDetail'],
    stores: ['Parsers'],

    getType: function(){
        return 'parser';
    }

});
