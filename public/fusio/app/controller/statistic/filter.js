'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, filter) {

  $scope.filter = filter;

  $scope.doFilter = function() {
    $uibModalInstance.close($scope.filter);
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $http.get(fusio.baseUrl + 'backend/routes')
    .then(function(response) {
      $scope.routes = response.data.entry;
    });

  $http.get(fusio.baseUrl + 'backend/app')
    .then(function(response) {
      $scope.apps = response.data.entry;
    });

};
