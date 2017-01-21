'use strict';

var angular = require('angular');

angular.module('fusioApp.account', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/account/change_password', {
    templateUrl: 'app/controller/account/change_password.html',
    controller: 'ChangePasswordCtrl'
  });
}])

.controller('ChangePasswordCtrl', require('./change_password'));
