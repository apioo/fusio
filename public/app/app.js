'use strict';

var fusioApp = angular.module('fusioApp', [
	'ngRoute',
	'ngSanitize',
	'ui.bootstrap',
	'ui.ace',
	'chart.js',
	'ng-showdown',
	'fusioApp.app',
	'fusioApp.connection',
	'fusioApp.action',
	'fusioApp.dashboard',
	'fusioApp.log',
	'fusioApp.login',
	'fusioApp.logout',
	'fusioApp.routes',
	'fusioApp.schema',
	'fusioApp.user',
	'fusioApp.scope'
]);

fusioApp.factory('fusioIsAuthenticated', ['$location', '$window', function($location, $window) {  
	return {
		responseError: function(response){
			if (response.status == 400 && response.data.message && response.data.message.indexOf('Invalid access token') !== -1) {
				$window.sessionStorage.removeItem('fusio_access_token');

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

fusioApp.config(['$showdownProvider', function($showdownProvider){
}]);

fusioApp.run(function ($rootScope, $window, $location, $http, helpLoader) {
	var token = $window.sessionStorage.getItem('fusio_access_token');
	if (token) {
		$http.defaults.headers.common['Authorization'] = 'Bearer ' + token;

		angular.element(document.querySelector('body')).removeClass('fusio-hidden');
	} else {
		$location.path('/login');
	}

	// make help loader global available
	$rootScope.help = helpLoader;
});
