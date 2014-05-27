
Ext.define('Fusio.controller.Navigation', {
    extend: 'Ext.app.Controller',

    views: ['Navigation'],

    init: function(){
        this.control({
            'navigation': {
                selectionchange: this.onNavigationSelect
            }
        });
    },

    onLaunch: function(){
        this.application.fireEvent('load_controller', {
            raw: {
                text: 'Model', 
                id: 'Fusio.controller.Model'
            }
        });
    },

    onNavigationSelect: function(selModel, selection) {
        this.application.fireEvent('load_controller', selection[0]);
    }

});
