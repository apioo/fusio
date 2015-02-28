'use strict';

angular.module('fusioApp.dashboard', ['ngRoute', 'chart.js'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'app/dashboard/index.html',
    controller: 'DashboardCtrl'
  });
}])

.controller('DashboardCtrl', ['$scope', '$http', function ($scope, $http) {

	$http.get(fusio_url + 'backend/dashboard/incoming_requests').success(function(data){
		$scope.incomingRequests = data;
	});

	$http.get(fusio_url + 'backend/dashboard/most_used_routes').success(function(data){
		$scope.mostUsedRoutes = data.entry;
	});

	$http.get(fusio_url + 'backend/dashboard/most_used_apps').success(function(data){
		$scope.mostUsedApps = data.entry;
	});

	$http.get(fusio_url + 'backend/dashboard/latest_requests').success(function(data){
		$scope.latestRequests = data.entry;
	});

	$http.get(fusio_url + 'backend/dashboard/latest_apps').success(function(data){
		$scope.latestApps = data.entry;
	});

	$http.get(fusio_url + 'backend/dashboard/latest_users').success(function(data){
		$scope.latestUsers = data.entry;
	});

}]);
