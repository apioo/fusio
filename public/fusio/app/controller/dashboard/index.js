'use strict';

var angular = require('angular');

angular.module('fusioApp.dashboard', ['ngRoute', 'chart.js'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'app/controller/dashboard/index.html',
    controller: 'DashboardCtrl'
  });
}])

.controller('DashboardCtrl', require('./dashboard'))

;
