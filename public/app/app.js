'use strict';

var fusioApp = angular.module('fusioApp', [
	'ngRoute',
	'ngSanitize',
	'ui.bootstrap',
	'ui.ace',
	'chart.js',
	'fusioApp.app',
	'fusioApp.connection',
	'fusioApp.action',
	'fusioApp.dashboard',
	'fusioApp.log',
	'fusioApp.login',
	'fusioApp.logout',
	'fusioApp.routes',
	'fusioApp.schema',
	'fusioApp.settings',
	'fusioApp.trigger',
	'fusioApp.user',
	'fusioApp.scope'
]);

fusioApp.factory('fusioIsAuthenticated', ['$location', function($location) {  
	return {
		responseError: function(response){
			if (response.status == 401) {
				$location.path('/login');
			}
			return response;
		}
	};
}]);

fusioApp.config(['$routeProvider', function($routeProvider){
	$routeProvider.
		otherwise({
			redirectTo: '/dashboard'
		});
}]);

fusioApp.config(['$httpProvider', function($httpProvider) {  
	$httpProvider.interceptors.push('fusioIsAuthenticated');
}]);

fusioApp.run(function ($window, $location, $http) {
	var token = $window.sessionStorage.getItem('fusio_access_token');
	if (token) {
		$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;

		angular.element(document.querySelector('body')).removeClass('fusio-hidden');
	} else {
		$location.path('/login');
	}
});
