'use strict';

var angular = require('angular');

angular.module('fusioApp.app', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/app', {
    templateUrl: 'app/controller/app/index.html',
    controller: 'AppCtrl'
  });
}])

.controller('AppCtrl', require('./app'))
.controller('AppCreateCtrl', require('./create'))
.controller('AppUpdateCtrl', require('./update'))
.controller('AppDeleteCtrl', require('./delete'))

;

