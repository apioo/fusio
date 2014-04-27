
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
					html+= '<label for="' + resp.parameters[i].name + '">' + resp.parameters[i].title + '</label>';
					html+= '<input type="text" class="form-control connection-params" name="' + resp.parameters[i].name + '" id="' + resp.parameters[i].name + '">';
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


fusio.action = {

	showModal: function(){

		// load actions
		$.get(psx_url + 'managment/action', function(resp){
			var html = '';
			for (var i = 0; i < resp.entry.length; i++) {
				html+= '<option value="' + resp.entry[i].id + '">' + resp.entry[i].name + ' (' + resp.entry[i].description + ')</option>';
			}
			$('#action').html(html);

			fusio.action.loadParam(resp.entry[0].id);
		});

		$('#action-modal').modal();

	},

	loadParam: function(actionId){
		$.get(psx_url + 'backend/api/add/parameters/' + actionId, function(resp){
			if (resp.parameters) {
				var html = '';
				for (var i = 0; i < resp.parameters.length; i++) {
					html+= '<div class="form-group">';
					html+= '<label for="' + resp.parameters[i].name + '">' + resp.parameters[i].title + '</label>';
					html+= '<input type="text" class="form-control connection-params" name="' + resp.parameters[i].name + '" id="' + resp.parameters[i].name + '">';
					html+= '</div>';
				}
				$('#action-param').html(html);

				fusio.action.loadConnection();
			}
		});
	},

	loadConnection: function(){
		if ($('#connection_id').length > 0) {
			$('#connection_id').attr('disabled', 'disabled');
			$.get(psx_url + 'managment/connection', function(resp){
				var html = '<select name="connection_id" id="connection_id" class="form-control" onchange="fusio.action.loadTable($(this).val())">';
				for (var i = 0; i < resp.entry.length; i++) {
					html+= '<option value="' + resp.entry[i].id + '">' + resp.entry[i].name + ' (' + resp.entry[i].type + ')</option>';
				}
				html+= '</select>';
				$('#connection_id').replaceWith(html);

				fusio.action.loadTable(resp.entry[0].id);
			});
		}
	},

	loadTable: function(connectionId){
		if ($('#table').length > 0) {
			$('#table').attr('disabled', 'disabled');
			$.get(psx_url + 'backend/api/add/tables/' + connectionId, function(resp){
				var html = '<select name="table" id="table" class="form-control" onchange="fusio.action.loadField($(this).val())">';
				for (var i = 0; i < resp.tables.length; i++) {
					html+= '<option value="' + resp.tables[i] + '">' + resp.tables[i] + '</option>';
				}
				html+= '</select>';
				$('#table').replaceWith(html);

				fusio.action.loadField(resp.tables[0]);
			});
		}
	},

	loadField: function(table){
		if ($('#fields').length > 0) {
			var connectionId = $('#connection_id').val();
			$('#fields').attr('disabled', 'disabled');
			$.get(psx_url + 'backend/api/add/fields/' + connectionId + '/' + table, function(resp){
				var html = '<div id="fields">';
				for (var i = 0; i < resp.fields.length; i++) {
					html+= '<div class="checkbox">';
					html+= '<label>';
					html+= '<input type="checkbox" name="fields[' + resp.fields[i] + ']" value="1" checked="checked"> ' + resp.fields[i];
					html+= '</label>';
					html+= '</div>';
				}
				html+= '</div>';
				$('#fields').replaceWith(html);
			});
		}

	},

	save: function(){
		var data = $('#action-form').serialize();
		$.post('/api/datasource', data, function(resp){
			$('#notification').html(fusio.getNotificationHtml(resp));
		});
	}

};
