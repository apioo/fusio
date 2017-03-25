'use strict';

var angular = require('angular');
var fusioApp = angular.module('fusioApp', [
  'ngRoute',
  'ngSanitize',
  'ngAnimate',
  'ngTagsInput',
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
  'fusioApp.error',
  'fusioApp.import',
  'fusioApp.log',
  'fusioApp.login',
  'fusioApp.logout',
  'fusioApp.rate',
  'fusioApp.routes',
  'fusioApp.schema',
  'fusioApp.scope',
  'fusioApp.statistic',
  'fusioApp.token',
  'fusioApp.user'
]);

require('angular-route');
require('angular-sanitize');
require('angular-animate');
require('angular-ui-bootstrap');
require('angular-ui-ace');
require('angular-chart.js');
require('angular-loading-bar');
require('angular-highlightjs');
require('ng-tags-input');
require('ng-showdown');
require('./controller/account');
require('./controller/action');
require('./controller/app');
require('./controller/config');
require('./controller/connection');
require('./controller/dashboard');
require('./controller/import');
require('./controller/error');
require('./controller/log');
require('./controller/login');
require('./controller/logout');
require('./controller/rate');
require('./controller/routes');
require('./controller/schema');
require('./controller/scope');
require('./controller/statistic');
require('./controller/token');
require('./controller/user');

fusioApp.value('version', require('../package.json').version);

fusioApp.factory('formBuilder', require('./service/form_builder'));
fusioApp.factory('helpLoader', require('./service/help_loader'));

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
    var pos = url.lastIndexOf('/fusio');
    if (pos !== -1) {
      url = url.substring(0, pos);
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

  var user = $window.sessionStorage.getItem('fusio_user');
  if (user) {
    $rootScope.user = JSON.parse(user);
  }

  // make help loader global available
  $rootScope.help = helpLoader;

  // set version
  $rootScope.version = version;

});

if (window) {
  window.fusioApp = fusioApp;
}

module.exports = fusioApp;
