'use strict';

var fusioApp = angular.module('fusioApp', [
	'ngRoute',
	'ui.bootstrap',
	'fusioApp.app',
	'fusioApp.connection',
	'fusioApp.controller',
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

