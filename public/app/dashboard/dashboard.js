'use strict';

angular.module('fusioApp.dashboard', ['ngRoute'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'app/dashboard/index.html',
    controller: 'DashboardCtrl'
  });
}])

.controller('DashboardCtrl', ['$scope', '$http', '$modal', function ($scope, $http, $modal) {

	function assignCurrentUser (user) {
		$rootScope.currentUser = user;
		return user;
	}

	var modalInstance = $modal.open({
		templateUrl: 'app/login/index.html',
		controller: 'LoginCtrl'
	});

	modalInstance.result.then(assignCurrentUser);

}]);
