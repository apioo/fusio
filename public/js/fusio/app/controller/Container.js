
Ext.define('Fusio.controller.Container', {
    extend: 'Ext.app.Controller',

    views: ['Container'],
	refs: [{
	    selector: 'container[cls~=fusio-container]',
	    ref: 'container'
	}],

    init: function() {
	    this.application.on({
	        load_controller: this.onLoadController,
	        scope: this
	    });
    },

    onLoadController: function(rec){
    	var container = this.getContainer();

    	// search for tab
    	var found = false;
    	container.items.each(function(c){
    		if (!found && c.hasCls('fusio-' + rec.raw.text.toLowerCase())) {
    			container.setActiveTab(c);
    			found = true;
    		}
    	});

    	if (!found) {
    		// if not found load tab
	    	var controller = this.getController(rec.raw.id);
	    	if (controller) {
		    	var c = container.add({
		    		title: rec.raw.text,
		    		xtype: rec.raw.text.toLowerCase(),
		    		border: false
		    	});

		    	container.setActiveTab(c);

		    	controller.onLaunch();
	    	} else {
				console.log('Unknown controller ' + rec.raw.id);
	    	}
    	}
    }

});
