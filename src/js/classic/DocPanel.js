Ext.define('Tualo.binary.docx.views.DocPanel', {
    extend: 'Ext.Panel',
    layout: 'fit',

    alias: 'widget.tualobinarydocxdocpanel',
    controller: 'tualobinarydocxdocpanel',
    viewModel: {
        type: 'tualobinarydocxdocpanel'
    },
    config: {
        documentId: null
    },
    applyDocumentId: function (id) {
        console.log('Doc: Document ID applied to:', id);
        this.getViewModel().set('documentId', id);
        //this.getController().onDocumentIdChange(id);
        this.loadDocument(id);
    },

    loadDocument: async function (id) {
        let json = await (await fetch('./binary-docx/register/' + id)).json();
        this.getComponent('docIframe').load('https://view.officeapps.live.com/op/embed.aspx?src=' + json.url);
    },

    items: [
        {
            itemId: 'docIframe',
            xtype: 'tualo_binary_docxiframe',
            src: 'about:blank',
        }
    ]
});

