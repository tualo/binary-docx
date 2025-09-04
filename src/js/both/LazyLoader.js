Ext.define('Tualo.binary.docx.LazyLoader', {
    singleton: true,
    requires: [
        'Ext.Loader'
    ]
});
Ext.Loader.setPath('Tualo.binary.docx.lazy', './jsbinarydocx');
