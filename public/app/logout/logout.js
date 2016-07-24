'use strict';

angular.module('fusioApp.logout', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/logout', {
    templateUrl: 'app/logout/index.html',
    controller: 'LogoutCtrl'
  });
}])

.controller('LogoutCtrl', ['$scope', '$http', '$location', '$window', 'fusio', function($scope, $http, $location, $window, fusio) {

  var removeToken = function() {
    delete $http.defaults.headers.common['Authorization'];

    $window.sessionStorage.removeItem('fusio_access_token');

    $location.path('/login');
  };

  $http.post(fusio.baseUrl + 'authorization/revoke', null)
    .success(removeToken)
    .error(removeToken);

}]);
