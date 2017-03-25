'use strict';

var angular = require('angular');

angular.module('fusioApp.token', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/token', {
    templateUrl: 'app/controller/token/index.html',
    controller: 'TokenCtrl'
  });
}])

.controller('TokenCtrl', require('./token'))
.controller('TokenDetailCtrl', require('./detail'))
.controller('TokenFilterCtrl', require('./filter'))

;
