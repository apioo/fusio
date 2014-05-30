
Ext.define('Fusio.view.Viewport', {
    extend: 'Ext.container.Viewport',
    requires: [
        'Fusio.view.Container',
        'Fusio.view.Navigation',
        'Fusio.view.DetailWindow'
    ],
    layout: 'border',
    initComponent: function() {
        this.items = [{
            region: 'north',
            title: '<div class="fusio-header"><div style="float:left;">Fusio</div></div>',
            margins: '0 0 0 0',
            border: false
        },{
            region: 'west',
            layout: 'fit',
            width: 200,
            minWidth: 175,
            maxWidth: 400,
            margins: '5 5 5 5',
            items: [{
                header: false,
                xtype: 'navigation'
            }]
        },{
            region: 'center',
            layout: 'fit',
            margins: '5 5 5 0',
            items: [{
                header: false,
                xtype: 'container'
            }]
        }];

        this.callParent();
    }
});
