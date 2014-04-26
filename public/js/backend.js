
var fusio = {

	getNotificationHtml: function(resp){
		var html;
		if (resp.success && resp.success == true) {
			var message = resp.message ? resp.message : 'Operation successful';
			html = '<div class="alert alert-success alert-dismissable">';
			html+= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html+= message;
			html+= '</div>';
		} else {
			var message = resp.message ? resp.message : 'An unknown error occured';
			html = '<div class="alert alert-danger alert-dismissable">';
			html+= '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			html+= message;
			html+= '</div>';
		}
		return html;
	},

	ucfirst: function(content){
		return content.charAt(0).toUpperCase() + content.slice(1);
	}

};

fusio.connection = {

	load: function(){
		$.get(psx_url + 'managment/connection', function(resp){
			var html = '';
			for (var i = 0; i < resp.entry.length; i++) {
				html+= '<tr>';
				html+= '<td>' + resp.entry[i].name + ' (' + resp.entry[i].type + ')</td>';
				html+= '<td><a href="#" onclick="fusio.connection.remove(' + resp.entry[i].id + ',this);return false;" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a></td>';
				html+= '</tr>';
			}
			$('#content tbody').html(html);
		});
	},

	showModal: function(){
		$.get(psx_url + 'backend/connection/types', function(resp){
			if (resp.types) {
				var html = '';
				for (var i = 0; i < resp.types.length; i++) {
					html+= '<option value="' + resp.types[i] + '">' + fusio.ucfirst(resp.types[i]) + '</option>';
				}
				$('#type').html(html);

				fusio.connection.loadParam(resp.types[0]);
			}
		});

		$('#connection-modal').modal();
	},

	loadParam: function(type){
		$.get(psx_url + 'backend/connection/parameters/' + type, function(resp){
			if (resp.parameters) {
				var html = '';
				for (var i = 0; i < resp.parameters.length; i++) {
					html+= '<div class="form-group">';
					html+= '<label for="' + resp.parameters[i] + '">' + fusio.ucfirst(resp.parameters[i]) + '</label>';
					html+= '<input type="text" class="form-control connection-params" name="' + resp.parameters[i] + '" id="' + resp.parameters[i] + '">';
					html+= '</div>';
				}
				$('#connection-param').html(html);
			}
		});
	},

	save: function(){
		var params = {};
		$('.connection-params').each(function(){
			params[$(this).attr('name')] = $(this).val();
		});

		var data = {
			name: $('#name').val(),
			type: $('#type').val(),
			param: JSON.stringify(params),
		};

		$.post(psx_url + 'managment/connection', data, function(resp){
			$('#notification').html(fusio.getNotificationHtml(resp));

			fusio.connection.load();
		});
	},

	remove: function(id, el){
		var data = {
			id: id
		};
		$.post(psx_url + 'managment/connection', data, function(resp){
			$('#notification').html(fusio.getNotificationHtml(resp));

			$(el).parent().parent().fadeOut();
		});
	}

};


fusio.datasource = {

	showModal: function(){

		// load connections
		$.get(psx_url + 'managment/connection', function(resp){
			var html = '';
			for (var i = 0; i < resp.entry.length; i++) {
				html+= '<option value="' + resp.entry[i].id + '">' + resp.entry[i].name + ' (' + resp.entry[i].type + ')</option>';
			}
			$('#connection').html(html);
		});

		$('#datasource-modal').modal();

	},

	loadParam: function(){
		var connectionId = $(this).val();
		$.get(psx_url + 'backend/datasource/parameters/' + connectionId, function(resp){
			if (resp.parameters) {
				var html = '';
				for (var i = 0; i < resp.parameters.length; i++) {
					html+= '<div class="form-group">';
					html+= '<label for="' + resp.parameters[i] + '">' + fusio.ucfirst(resp.parameters[i]) + '</label>';
					html+= '<input type="text" class="form-control connection-params" name="' + resp.parameters[i] + '" id="' + resp.parameters[i] + '">';
					html+= '</div>';
				}
				$('#datasource-param').html(html);
			}
		});
	},

	save: function(){
		var data = $('#datasource-form').serialize();
		$.post('/api/datasource', data, function(resp){
			$('#notification').html(fusio.getNotificationHtml(resp));
		});
	}

};
