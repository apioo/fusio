
Ext.define('Fusio.view.DetailWindow', {
    extend: 'Ext.window.Window',

    alias: 'widget.detail_window',

    title: 'Details',
    height: 600,
    width: 800,
    modal: true,
    layout: 'fit',
    resizeable: false,
    closeAction: 'destroy',
    cls: 'fusio-detail-window'
});
