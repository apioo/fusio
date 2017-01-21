'use strict';

var angular = require('angular');

angular.module('fusioApp.login', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/login', {
    templateUrl: 'app/controller/login/index.html',
    controller: 'LoginCtrl'
  });
}])

.controller('LoginCtrl', require('./login'))

;
