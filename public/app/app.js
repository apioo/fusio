'use strict';

var fusioApp = angular.module('fusioApp', [
	'ngRoute',
	'ngSanitize',
	'ui.bootstrap',
	'ui.ace',
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
	'fusioApp.user',
	'fusioApp.scope'
]);

fusioApp.config(['$routeProvider', function($routeProvider){
	$routeProvider.
		otherwise({
			redirectTo: '/dashboard'
		});
}]);

