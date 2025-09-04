Ext.define('Tualo.binary.docx.views.DocPanel', {
    extend: 'Ext.Panel',
    layout: 'fit',
    items: [
        {
            xtype: 'tualo_binary_docxiframe',
            src: 'https://fb-wvd.tualo.io/server/binary-docx/open/6496',
        }
    ]
});

