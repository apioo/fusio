'use strict';

angular.module('fusioApp.app', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/app', {
    templateUrl: 'app/app/index.html',
    controller: 'AppCtrl'
  });
}])

.controller('AppCtrl', ['$scope', '$http', function($scope, $http){

}]);
