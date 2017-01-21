'use strict';

var angular = require('angular');

angular.module('fusioApp.connection', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/connection', {
    templateUrl: 'app/controller/connection/index.html',
    controller: 'ConnectionCtrl'
  });
}])

.controller('ConnectionCtrl', require('./connection'))
.controller('ConnectionCreateCtrl', require('./create'))
.controller('ConnectionUpdateCtrl', require('./update'))
.controller('ConnectionDeleteCtrl', require('./delete'))

;

