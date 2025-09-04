Ext.define('Tualo.routes.binary.docx.Viewer', {
    statics: {
        load: async function () {
            return [
                {
                    name: 'DOCX Viewer ',
                    path: '#binary-docx/:{id}'
                }
            ]
        }
    },
    url: 'binary-docx/:{id}',
    handler: {

        action: function (values) {
            Ext.getApplication().addView('Tualo.binary.docx.views.DocPanel', {
                id: values.id
            });
        },
        before: function (values, action) {
            action.resume();

        },


    }
});

Ext.define('Tualo.routes.FibuConv', {
    url: 'binary-docx',
    handler: {
        action: function (token) {
            console.log('onAnyRoute', token);
            alert('fibuconv', 'ok');
        },
        before: function (action) {
            console.log('onBeforeToken', action);
            console.log(new Date());
            action.resume();
        }
    }
});