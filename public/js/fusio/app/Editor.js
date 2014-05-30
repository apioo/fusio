
Ext.define('Fusio.Editor', {
    extend: 'Ext.form.field.Text',

    alias: 'widget.aceeditor',
    editorId: null,
    editor: null,

    initComponent: function(){
        var me = this;
        me.editorId = Ext.id();

        var config = {
            cls: 'wb-content-form-editor',
            fieldSubTpl: [
                '<div id="{id}" {inputAttrTpl}>',
                    '<div id="' + me.editorId + '" class="fusio-ace-editor"></div>',
                '</div>',
                {
                    disableFormats: true
                }
            ]
        };
        Ext.apply(me, config);

        me.callParent();
    },

    afterRender: function(){
        var me = this;
        me.callParent(arguments);

        // set width and height
        var editor = Ext.select('#' + me.editorId);
        editor.setWidth(this.getWidth());
        editor.setHeight(this.getHeight());

        // ace editor
        me.editor = ace.edit(me.editorId);
        me.editor.setTheme('ace/theme/eclipse');
        me.editor.getSession().setMode('ace/mode/sql');
        me.editor.setValue(me.rawValue, -1);
    },

    setRawValue: function(value){
        var me = this;
        me.rawValue = value;
        return value;
    },

    getRawValue: function(){
        var me = this;
        var value = me.editor != null ? me.editor.getValue() : '';
        return value;
    }

});
