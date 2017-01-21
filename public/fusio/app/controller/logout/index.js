'use strict';

var angular = require('angular');

angular.module('fusioApp.logout', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/logout', {
    templateUrl: 'app/controller/logout/index.html',
    controller: 'LogoutCtrl'
  });
}])

.controller('LogoutCtrl', require('./logout'))

;
