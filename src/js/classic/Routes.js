Ext.define('Tualo.routes.FibuConv',{
    url: 'fibuconv',
    handler: {
        action: function(token){
            console.log('onAnyRoute',token);
            alert('fibuconv','ok');
        },
        before: function (action) {
            console.log('onBeforeToken',action);
            console.log(new Date());
            action.resume();
        }
    }
});