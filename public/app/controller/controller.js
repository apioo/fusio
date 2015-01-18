'use strict';

angular.module('fusioApp.controller', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/controller', {
    templateUrl: 'app/controller/index.html',
    controller: 'ControllerCtrl'
  });
}])

.controller('ControllerCtrl', ['$scope', '$http', function($scope, $http){

}]);
