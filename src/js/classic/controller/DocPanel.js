Ext.define('Tualo.binary.docx.controller.DocPanel', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.tualobinarydocxdocpanel',
    onResize: function (me, width, height, oldWidth, oldHeight, eOpts) {
        this.view.updateLayout();
    },
    onBoxReady: function () {

        console.log('documentId', this.getViewModel().get('documentId'));
    },

});
