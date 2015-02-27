'use strict';

angular.module('fusioApp.login', ['ngRoute'])

.config(['$routeProvider', function ($routeProvider) {
	$routeProvider.when('/login', {
		templateUrl: 'app/login/index.html',
		controller: 'LoginCtrl'
	});
}])

.controller('LoginCtrl', ['$scope', '$http', '$location', function ($scope, $http, $location) {
	$scope.credentials = {
		username: '',
		password: ''
	};

	$scope.response = null;

	$scope.login = function(credentials){
		var req = {
			method: 'POST',
			url: fusio_url + 'backend/token',
			headers: {
				authorization: 'Basic ' + btoa(credentials.username + ':' + credentials.password)
			},
			data: 'grant_type=client_credentials'
		};

		$http(req)
			.success(function(data){
				if (data.access_token) {
					$http.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;
					$location.path('/dashboard');
				} else {
					$scope.response = data.error_description ? data.error_description : 'Authentication failed';
				}
			})
			.error(function(data){
				$scope.response = data.error_description ? data.error_description : 'Authentication failed';
			});
	};
}]);
