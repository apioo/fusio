'use strict';

angular.module('fusioApp.connection', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/connection', {
    templateUrl: 'app/connection/index.html',
    controller: 'ConnectionCtrl'
  });
}])

.controller('ConnectionCtrl', ['$scope', '$http', function($scope, $http){

}]);
