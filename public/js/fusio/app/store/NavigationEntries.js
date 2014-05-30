
Ext.define('Fusio.store.NavigationEntries', {
    extend: 'Ext.data.TreeStore',
    root: {
        expanded: true,
        text: 'Fusio',
        children: [{ 
            text: 'Endpoint', 
            expanded: true,
            children: [{
                text: 'Api', 
                id: 'Fusio.controller.Api',
                leaf: true 
            },{
                text: 'Model', 
                id: 'Fusio.controller.Model',
                leaf: true 
            },{
                text: 'View', 
                id: 'Fusio.controller.View',
                leaf: true 
            },{
                text: 'Trigger', 
                id: 'Fusio.controller.Trigger',
                leaf: true 
            }]
        },{
            text: 'System', 
            expanded: true, 
            children: [{
                text: 'Connection', 
                id: 'Fusio.controller.Connection',
                leaf: true 
            },{
                text: 'App', 
                id: 'Fusio.controller.App',
                leaf: true 
            },{
                text: 'User', 
                id: 'Fusio.controller.User',
                leaf: true 
            },{
                text: 'Settings', 
                id: 'Fusio.controller.Settings',
                leaf: true 
            },{
                text: 'Log', 
                id: 'Fusio.controller.Log',
                leaf: true 
            }]
        }]
    }
});
