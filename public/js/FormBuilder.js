
fusioApp.factory('formBuilder', ['$sce', '$compile', function($sce, $compile){
	var builder = {};
 
	builder.buildHtml = function(elements){
		if (!elements || !angular.isArray(elements) || elements.length === 0) {
			return '';
		}

		var form = '<div>';
		for (var i = 0; i < elements.length; i++) {
			var el = elements[i];
			if (!el.name || !el.title) {
				continue;
			}

			if (el.element == 'http://fusio-project.org/ns/2015/form/textarea') {
				form+= '<div class="form-group">';
				form+= '	<label for="' + el.name + '">' + el.title + ':</label>';
				form+= '	<textarea name="' + el.name + '" id="' + el.name + '" ng-model="action.config.' + el.name + '" rows="5" class="form-control">' + (el.value ? el.value : '') + '</textarea>';
				form+= '</div>';
			} else if (el.element == 'http://fusio-project.org/ns/2015/form/input') {
				form+= '<div class="form-group">';
				form+= '	<label for="' + el.name + '">' + el.title + ':</label>';
				form+= '	<input type="' + el.type + '" name="' + el.name + '" id="' + el.name + '" ng-model="action.config.' + el.name + '" value="' + (el.value ? el.value : '') + '" class="form-control" />';
				form+= '</div>';
			} else if (el.element == 'http://fusio-project.org/ns/2015/form/select') {
				form+= '<div class="form-group">';
				form+= '	<label for="' + el.name + '">' + el.title + ':</label>';
				form+= '	<select name="' + el.name + '" id="' + el.name + '" ng-model="action.config.' + el.name + '" class="form-control">';
				var values = el.value;
				if (angular.isArray(values)) {
					for (var j = 0; j < values.length; j++) {
						form+= '<option value="' + values[j].key + '">' + values[j].value + '</option>';
					}
				}
				form+= '	</select>';
				form+= '</div>';
			} else {
				form+= '<div class="form-group">';
				form+= '	<label for="' + el.name + '">' + el.title + ':</label>';
				form+= '	<input type="text" name="' + el.name + '" id="' + el.name + '" ng-model="action.config.' + el.name + '" value="' + (el.value ? el.value : '') + '" class="form-control" />';
				form+= '</div>';
			}
		}
		form+= '</div>';

		return $compile(form);
		//return $sce.trustAsHtml(form);
	};

	return builder;
}]);

