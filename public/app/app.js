'use strict';

var fusioApp = angular.module('fusioApp', [
  'ngRoute',
  'ngSanitize',
  'ngAnimate',
  'ui.bootstrap',
  'ui.ace',
  'chart.js',
  'ng-showdown',
  'hljs',
  'angular-loading-bar',
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

fusioApp.provider('fusio', function() {
  var baseUrl = null;

  this.setBaseUrl = function(_baseUrl) {
    baseUrl = _baseUrl;
  };

  /**
   * Simple helper function to guess the API endpoint url
   */
  this.guessFusioEndpointUrl = function(urlRewrite) {
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
    return url + (urlRewrite ? '/' : '/index.php/');
  };

  this.$get = function() {
    // BC workaround if the base url was not configured but the fusio_url is
    // available we use it else we guess the url
    if (baseUrl === null && typeof fusio_url !== 'undefined') {
      baseUrl = fusio_url;
    } else if (baseUrl === null) {
      baseUrl = this.guessFusioEndpointUrl(false);
    }

    return {
      baseUrl: baseUrl
    };
  };
});

fusioApp.factory('fusioIsAuthenticated', ['$location', '$window', '$q', function($location, $window, $q) {
  return {
    responseError: function(response) {
      if (response.status == 400 && response.data.message && response.data.message.indexOf('Invalid access token') !== -1) {
        $window.sessionStorage.removeItem('fusio_access_token');

        $location.path('/login');
      }

      return $q.reject(response);
    }
  };
}]);

fusioApp.config(['$routeProvider', function($routeProvider) {
  $routeProvider.otherwise({
    redirectTo: '/dashboard'
  });
}]);

fusioApp.config(['$httpProvider', function($httpProvider) {
  $httpProvider.interceptors.push('fusioIsAuthenticated');
}]);

fusioApp.config(['$showdownProvider', function($showdownProvider) {
}]);

fusioApp.config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
  cfpLoadingBarProvider.includeBar = false;
  cfpLoadingBarProvider.includeSpinner = true;
  cfpLoadingBarProvider.parentSelector = '.fusio-loading-container';
}]);

fusioApp.run(function($rootScope, $window, $location, $http, helpLoader, version) {
  var token = $window.sessionStorage.getItem('fusio_access_token');
  if (token) {
    $http.defaults.headers.common['Authorization'] = 'Bearer ' + token;

    $rootScope.userAuthenticated = true;
  } else {
    $location.path('/login');
  }

  // make help loader global available
  $rootScope.help = helpLoader;

  // set version
  $rootScope.version = version;

});
