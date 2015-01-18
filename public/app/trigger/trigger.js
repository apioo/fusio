'use strict';

angular.module('fusioApp.trigger', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/trigger', {
    templateUrl: 'app/trigger/index.html',
    controller: 'TriggerCtrl'
  });
}])

.controller('TriggerCtrl', ['$scope', '$http', function($scope, $http){

}]);
