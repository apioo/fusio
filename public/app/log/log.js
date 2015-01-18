'use strict';

angular.module('fusioApp.log', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/log', {
    templateUrl: 'app/log/index.html',
    controller: 'LogCtrl'
  });
}])

.controller('LogCtrl', ['$scope', '$http', function($scope, $http){

}]);
