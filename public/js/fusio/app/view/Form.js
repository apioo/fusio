
Ext.define('Fusio.view.Form', {
    extend: 'Ext.panel.Panel',

    alias: 'widget.form',

    title: 'Form',
    border: false,
    cls: 'fusio-form',

    initComponent: function(){

        this.items = [{
            border: false,
            html: 'Form content'
        }];

        this.callParent();
    }

});
