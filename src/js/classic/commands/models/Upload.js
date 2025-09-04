Ext.define('Tualo.fibuconv.commands.models.Upload', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.fibuconv_upload_command',
    stores: {
        files: 
            {
                type: 'store',
                fields: ['name', 'size', 'file', 'status']
            }
        
    }
});
