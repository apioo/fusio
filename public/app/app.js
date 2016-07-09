'use strict';

var fusioApp = angular.module('fusioApp', [
    'ngRoute',
    'ngSanitize',
    'ui.bootstrap',
    'ui.ace',
    'chart.js',
    'ng-showdown',
    'fusioApp.account',
    'fusioApp.action',
    'fusioApp.app',
    'fusioApp.config',
    'fusioApp.connection',
    'fusioApp.dashboard',
    'fusioApp.database',
    'fusioApp.import',
    'fusioApp.log',
    'fusioApp.login',
    'fusioApp.logout',
    'fusioApp.routes',
    'fusioApp.schema',
    'fusioApp.scope',
    'fusioApp.statistic',
    'fusioApp.user'
]);

fusioApp.value('version', 'v0.3');

fusioApp.factory('fusioIsAuthenticated', ['$location', '$window', '$q', function($location, $window, $q) {  
    return {
        responseError: function(response){
            if (response.status == 400 && response.data.message && response.data.message.indexOf('Invalid access token') !== -1) {
                $window.sessionStorage.removeItem('fusio_access_token');

                $location.path('/login');
            }

            return $q.reject(response);
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

fusioApp.run(function ($rootScope, $window, $location, $http, helpLoader, version) {
    var token = $window.sessionStorage.getItem('fusio_access_token');
    if (token) {
        $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;

        angular.element(document.querySelector('body')).removeClass('fusio-hidden');
    } else {
        $location.path('/login');
    }

    // make help loader global available
    $rootScope.help = helpLoader;

    // set version
    $rootScope.version = version;
});

/**
 * Simple helper function to guess the API endpoint url
 */
function guessFusioEndpointUrl() {
    var url = window.location.href;
    var removePart = function(url, sign) {
        var count = (url.match(/\//g) || []).length;
        var pos = url.lastIndexOf(sign);
        if (count > 2 && pos !== -1) {
            return url.substring(0, pos);
        }
        return url;
    };
    var parts = ['#', '?', '/'];
    for (var i = 0; i < parts.length; i++) {
        url = removePart(url, parts[i]);
    }
    return url + '/index.php/';
}

if (typeof fusio_url === 'undefined') {
    var fusio_url = guessFusioEndpointUrl();
}
