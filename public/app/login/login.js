'use strict';

angular.module('fusioApp.login', ['ngRoute'])

.config(['$routeProvider', function ($routeProvider) {
	$routeProvider.when('/login', {
		templateUrl: 'app/login/index.html',
		controller: 'LoginCtrl'
	});
}])

.controller('LoginCtrl', ['$scope', '$http', function ($scope, $http) {
	$scope.credentials = {
		username: '',
		password: ''
	};

	$scope.login = function (credentials) {

		console.log(credentials);

		/*
		$http.post('api', $scope.master)
			.success(function(data){
				$scope.response = data;
				$scope.reset();

				window.location.hash = '/';
			})
			.error(function(data){
				$scope.response = data;
			});
		*/

		return false;

	}
}]);
