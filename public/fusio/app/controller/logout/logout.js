'use strict';

module.exports = function($scope, $http, $location, $window, $rootScope, fusio) {

  var removeToken = function(response) {
    delete $http.defaults.headers.common['Authorization'];

    $window.sessionStorage.removeItem('fusio_access_token');
    $window.sessionStorage.removeItem('fusio_user');

    $rootScope.userAuthenticated = false;

    $location.path('/login');
  };

  $http.post(fusio.baseUrl + 'authorization/revoke', null)
    .then(removeToken)
    .catch(removeToken);

};
