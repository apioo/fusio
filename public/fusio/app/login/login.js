'use strict';

angular.module('fusioApp.login', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/login', {
    templateUrl: 'app/login/index.html',
    controller: 'LoginCtrl'
  });
}])

.controller('LoginCtrl', ['$scope', '$http', '$location', '$window', '$rootScope', 'fusio', function($scope, $http, $location, $window, $rootScope, fusio) {
  $scope.credentials = {
    username: '',
    password: ''
  };

  $scope.response = null;
  $scope.loading = false;

  $scope.login = function(credentials) {
    $scope.loading = true;

    var req = {
      method: 'POST',
      url: fusio.baseUrl + 'backend/token',
      headers: {
        authorization: 'Basic ' + btoa(credentials.username + ':' + credentials.password)
      },
      data: 'grant_type=client_credentials'
    };

    $http(req)
      .then(function(response) {
        var data = response.data;
        $scope.loading = false;
        if (data.access_token) {
          $http.defaults.headers.common['Authorization'] = 'Bearer ' + data.access_token;

          // store access token
          $window.sessionStorage.setItem('fusio_access_token', data.access_token);

          $location.path('/dashboard');

          $rootScope.userAuthenticated = true;
        } else {
          $scope.response = data.error_description ? data.error_description : 'Authentication failed';
        }
      })
      .catch(function(response) {
        var data = response.data;
        $scope.loading = false;
        $scope.response = data.error_description ? data.error_description : 'Authentication failed';
      });
  };
}]);
