'use strict';

var angular = require('angular');

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/schema', {
    templateUrl: 'app/controller/schema/index.html',
    controller: 'SchemaCtrl'
  });
  $routeProvider.when('/schema/designer/:schema_id', {
    templateUrl: 'app/controller/schema/designer.html',
    controller: 'SchemaDesignerCtrl'
  });
}])

.controller('SchemaCtrl', require('./schema'))
.controller('SchemaCreateCtrl', require('./create'))
.controller('SchemaUpdateCtrl', require('./update'))
.controller('SchemaDeleteCtrl', require('./delete'))
.controller('SchemaDesignerCtrl', require('./designer'))

;
