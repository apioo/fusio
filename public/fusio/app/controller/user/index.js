'use strict';

var angular = require('angular');

angular.module('fusioApp.user', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/user', {
    templateUrl: 'app/controller/user/index.html',
    controller: 'UserCtrl'
  });
}])

.controller('UserCtrl', require('./user'))
.controller('UserCreateCtrl', require('./create'))
.controller('UserUpdateCtrl', require('./update'))
.controller('UserDeleteCtrl', require('./delete'))

;
