
Ext.define('Fusio.controller.Navigation', {
    extend: 'Ext.app.Controller',

    views: ['Navigation'],
    stores: ['NavigationEntries'],
    refs: [{
        selector: 'navigation',
        ref: 'navigation'
    }],

    init: function(){
        this.control({
            'navigation': {
                selectionchange: this.onNavigationSelect
            }
        });
    },

    onLaunch: function(){
        this.getNavigation().getSelectionModel().select(1);
    },

    onNavigationSelect: function(selModel, selection) {
        this.application.fireEvent('load_controller', selection[0]);
    }

});
