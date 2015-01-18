'use strict';

angular.module('fusioApp.schema', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/schema', {
    templateUrl: 'app/schema/index.html',
    controller: 'SchemaCtrl'
  });
}])

.controller('SchemaCtrl', ['$scope', '$http', function($scope, $http){

}]);
