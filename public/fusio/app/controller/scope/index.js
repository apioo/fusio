'use strict';

var angular = require('angular');

angular.module('fusioApp.scope', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/scope', {
    templateUrl: 'app/controller/scope/index.html',
    controller: 'ScopeCtrl'
  });
}])

.controller('ScopeCtrl', require('./scope'))
.controller('ScopeCreateCtrl', require('./create'))
.controller('ScopeUpdateCtrl', require('./update'))
.controller('ScopeDeleteCtrl', require('./delete'))

;
