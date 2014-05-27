
Ext.define('Fusio.view.Container', {
    extend: 'Ext.tab.Panel',

    alias: 'widget.container',

    title: 'Container',
    border: false,
    cls: 'fusio-container',
    minTabWidth: 64,

    initComponent: function(){
        this.callParent();
    }

});
