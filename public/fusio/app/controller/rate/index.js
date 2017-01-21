'use strict';

var angular = require('angular');

angular.module('fusioApp.rate', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/rate', {
    templateUrl: 'app/controller/rate/index.html',
    controller: 'RateCtrl'
  });
}])

.controller('RateCtrl', require('./rate'))
.controller('RateCreateCtrl', require('./create'))
.controller('RateUpdateCtrl', require('./update'))
.controller('RateDeleteCtrl', require('./delete'))

;
