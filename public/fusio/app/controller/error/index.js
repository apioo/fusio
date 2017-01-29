'use strict';

var angular = require('angular');

angular.module('fusioApp.error', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/error', {
    templateUrl: 'app/controller/error/index.html',
    controller: 'ErrorCtrl'
  });
}])

.controller('ErrorCtrl', require('./error'))
.controller('ErrorDetailCtrl', require('./detail'))

;
