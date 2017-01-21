'use strict';

var angular = require('angular');

angular.module('fusioApp.config', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/config', {
    templateUrl: 'app/controller/config/index.html',
    controller: 'ConfigCtrl'
  });
}])

.controller('ConfigCtrl', require('./config'))
.controller('ConfigUpdateCtrl', require('./update'))

;
