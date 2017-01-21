'use strict';

var angular = require('angular');

angular.module('fusioApp.import', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/import', {
    templateUrl: 'app/controller/import/index.html',
    controller: 'ImportCtrl'
  });
}])

.controller('ImportCtrl', require('./import'))
.controller('ImportPreviewCtrl', require('./preview'))
.controller('ImportRouteCtrl', require('./route'))
.controller('ImportActionCtrl', require('./action'))
.controller('ImportSchemaCtrl', require('./schema'))

;


