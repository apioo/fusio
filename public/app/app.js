'use strict';

var fusioApp = angular.module('fusioApp', [
	'ngRoute',
	'ngSanitize',
	'ui.bootstrap',
	'fusioApp.app',
	'fusioApp.connection',
	'fusioApp.action',
	'fusioApp.dashboard',
	'fusioApp.log',
	'fusioApp.login',
	'fusioApp.routes',
	'fusioApp.schema',
	'fusioApp.settings',
	'fusioApp.trigger',
	'fusioApp.user'
]);

fusioApp.config(['$routeProvider', function($routeProvider){
	$routeProvider.
		otherwise({
			redirectTo: '/dashboard'
		});
}]);
