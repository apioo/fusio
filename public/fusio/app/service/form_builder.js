'use strict';

module.exports = function($sce, $compile) {
  var builder = {};

  builder.buildHtml = function(elements, propertyName) {
    if (!elements || !angular.isArray(elements) || elements.length === 0) {
      return null;
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

      form += '<div class="form-group">';

      if (el.element == 'http://fusio-project.org/ns/2015/form/textarea') {
        form += '<label for="config-' + el.name + '">' + el.title + ':</label>';
        form += '<div ui-ace="{mode: \'' + el.mode + '\', workerPath: \'./node_modules/ace-builds/src-min-noconflict\'}" ng-model="' + propertyName + '.' + el.name + '" id="config-' + el.name + '" aria-describedby="' + helpId + '"></div>';
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/input') {
        form += '<label for="config-' + el.name + '">' + el.title + ':</label>';
        form += '<input type="' + el.type + '" name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" aria-describedby="' + helpId + '" class="form-control" />';
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/select') {
        form += '<label for="config-' + el.name + '">' + el.title + ':</label>';
        form += '<select name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" aria-describedby="' + helpId + '" class="form-control">';
        var options = el.options;
        if (angular.isArray(options)) {
          for (var j = 0; j < options.length; j++) {
            form += '<option value="' + options[j].key + '">' + options[j].value + '</option>';
          }
        }
        form += '</select>';
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/tag') {
        form += '<label for="config-' + el.name + '">' + el.title + ':</label>';
        form += '<tags-input ng-model="' + propertyName + '.' + el.name + '" placeholder="Add a ' + el.title + '"></tags-input>';
      } else {
        form += '<label for="config-' + el.name + '">' + el.title + ':</label>';
        form += '<input type="text" name="config-' + el.name + '" id="config-' + el.name + '" ng-model="' + propertyName + '.' + el.name + '" aria-describedby="' + helpId + '" class="form-control" />';
      }

      if (el.help) {
        form += '<span class="help-block" id="' + helpId + '">' + el.help + '</span>';
      }

      form += '</div>';
    }
    form += '</div>';

    return $compile(form);
  };

  builder.preProcessModel = function(data, elements){
    var model = {};

    for (var i = 0; i < elements.length; i++) {
      var el = elements[i];
      if (!el.name || !el.title) {
        continue;
      }

      if (!data[el.name]) {
        continue;
      }

      var value = data[el.name];
      if (el.element == 'http://fusio-project.org/ns/2015/form/textarea') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/input') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/select') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/tag') {
        var tags = [];
        if (angular.isArray(value)) {
          tags = value.map(function(val){
            return {
              text: val
            };
          });
        }

        model[el.name] = tags;
      } else {
        model[el.name] = value;
      }
    }

    return model;
  };

  builder.postProcessModel = function(data, elements){
    var model = {};

    for (var i = 0; i < elements.length; i++) {
      var el = elements[i];
      if (!el.name || !el.title) {
        continue;
      }

      if (!data[el.name]) {
        continue;
      }

      var value = data[el.name];
      if (el.element == 'http://fusio-project.org/ns/2015/form/textarea') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/input') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/select') {
        model[el.name] = value;
      } else if (el.element == 'http://fusio-project.org/ns/2015/form/tag') {
        var tags = [];
        if (angular.isArray(value)) {
          tags = value.map(function(val){
            return val.text;
          });
        }

        model[el.name] = tags;
      } else {
        model[el.name] = value;
      }
    }

    return model;
  };

  return builder;
};
