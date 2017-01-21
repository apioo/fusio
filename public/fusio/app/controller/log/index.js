'use strict';

var angular = require('angular');

angular.module('fusioApp.log', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/log', {
    templateUrl: 'app/controller/log/index.html',
    controller: 'LogCtrl'
  });
}])

.controller('LogCtrl', require('./log'))
.controller('LogDetailCtrl', require('./detail'))

;
