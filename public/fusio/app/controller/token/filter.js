'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, filter) {

  $scope.filter = filter;
  $scope.apps = [];
  $scope.users = [];

  $scope.doFilter = function() {
    $uibModalInstance.close($scope.filter);
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.getApps = function(name) {
    $http.get(fusio.baseUrl + 'backend/app?count=1024')
      .then(function(response) {
        $scope.apps = response.data.entry;
      });
  };

  $scope.getUsers = function(name) {
    $http.get(fusio.baseUrl + 'backend/user?count=1024')
      .then(function(response) {
        $scope.users = response.data.entry;
      });
  };

  $scope.getApps();
  $scope.getUsers();
  
};
