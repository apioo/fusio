
Ext.define('Fusio.DetailPanel', {
    extend: 'Ext.form.Panel',

    alias: 'widget.detail',

    title: 'Detail',
    border: false,
    cls: 'fusio-detail',
    defaultType: 'textfield',
    bodyPadding: 5,

    initComponent: function() {
        this.buttons = [{
            text: 'Reset',
            scope: this,
            handler: function(){
                this.getForm().reset();
            }
        },{
            text: 'Submit',
            formBind: true,
            disabled: true,
            scope: this,
            handler: function(){
                this.fireEvent('record_' + this.type, this);
            }
        }];

        this.items = this.getDefaultItems();

        this.callParent();
    },

    getDefaultItems: function(){
        console.log('You have to overwrite the getDefaultItems method');
        return null;
    }

});
