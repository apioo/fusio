'use strict';

angular.module('fusioApp.user', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/user', {
    templateUrl: 'app/user/index.html',
    controller: 'UserCtrl'
  });
}])

.controller('UserCtrl', ['$scope', '$http', function($scope, $http){

}]);
