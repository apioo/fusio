
fusioApp.factory('formBuilder', ['$sce', '$compile', function($sce, $compile){
	var builder = {};
 
	builder.buildHtml = function(elements, propertyName){
		if (!elements || !angular.isArray(elements) || elements.length === 0) {
			return '';
		}

		var form = '<div>';
		for (var i = 0; i < elements.length; i++) {
			var el = elements[i];
			if (!el.name || !el.title) {
				continue;
			}

			var helpId;
			if (el.help) {
				helpId = 'help-' + el.name;
			}

			form+= '<div class="form-group">';

			if (el.element == 'http://fusio-project.org/ns/2015/form/textarea') {
				form+= '<label for="config-' + el.name + '">' + el.title + ':</label>';
				form+= '<div ui-ace="{mode: \'' + el.mode + '\'}" ng-model="' + propertyName + '.' + el.name + '" id="config-' + el.name + '" aria-describedby="' + helpId + '"></div>';
			} else if (el.element == 'http://fusio-project.org/ns/2015/form/input') {
				form+= '<label for="config-' + el.name + '">' + el.title + ':</label>';
				form+= '<input type="' + el.type + '" name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" value="' + (el.value ? el.value : '') + '" aria-describedby="' + helpId + '" class="form-control" />';
			} else if (el.element == 'http://fusio-project.org/ns/2015/form/select') {
				form+= '<label for="config-' + el.name + '">' + el.title + ':</label>';
				form+= '<select name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" aria-describedby="' + helpId + '" class="form-control">';
				var options = el.options;
				if (angular.isArray(options)) {
					for (var j = 0; j < options.length; j++) {
						form+= '<option value="' + options[j].key + '">' + options[j].value + '</option>';
					}
				}
				form+= '</select>';
			} else {
				form+= '<label for="config-' + el.name + '">' + el.title + ':</label>';
				form+= '<input type="text" name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" value="' + (el.value ? el.value : '') + '" aria-describedby="' + helpId + '" class="form-control" />';
			}

			if (el.help) {
				form+= '<span class="help-block" id="' + helpId + '">' + el.help + '</span>';
			}

			form+= '</div>';
		}
		form+= '</div>';

		return $compile(form);
	};

	return builder;
}]);

