'use strict';

var angular = require('angular');

angular.module('fusioApp.statistic', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/statistic', {
    templateUrl: 'app/controller/statistic/index.html',
    controller: 'StatisticCtrl'
  });
}])

.controller('StatisticCtrl', require('./statistic'))
.controller('StatisticFilterCtrl', require('./filter'))

;
