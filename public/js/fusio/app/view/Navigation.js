
Ext.define('Fusio.view.Navigation', {
    extend: 'Ext.tree.Panel',

    alias: 'widget.navigation',

    title: 'Navigation',
    border: 0,
    rootVisible: false,
    cls: 'fusio-navigation',

    initComponent: function() {
    	this.store = 'NavigationEntries';

    	this.callParent();
    }
});