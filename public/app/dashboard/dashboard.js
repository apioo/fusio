'use strict';

angular.module('fusioApp.dashboard', ['ngRoute'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'app/dashboard/index.html',
    controller: 'DashboardCtrl'
  });
}])

.controller('DashboardCtrl', ['$scope', '$http', function ($scope, $http) {

	/*
	var modalInstance = $modal.open({
		templateUrl: 'app/login/index.html',
		controller: 'LoginCtrl'
	});

	modalInstance.result.then(assignCurrentUser);
	*/



}]);
