'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio) {

  $scope.user = {
    status: 0,
    name: '',
    email: '',
    scopes: []
  };

  $scope.statuuus = [{
    id: 0,
    name: 'Consumer'
  }, {
    id: 1,
    name: 'Administrator'
  }, {
    id: 2,
    name: 'Disabled'
  }];

  $scope.scopes = [];

  $http.get(fusio.baseUrl + 'backend/scope?count=1024')
    .then(function(response) {
      $scope.scopes = response.data.entry;
    });

  $scope.create = function(user) {
    var data = angular.copy(user);

    // remove app data
    if (data.apps) {
      delete data.apps;
    }

    // filter scopes
    if (data.scopes && angular.isArray(data.scopes)) {
      data.scopes = data.scopes.filter(function(value) {
        return value !== null;
      });
    }

    $http.post(fusio.baseUrl + 'backend/user', data)
      .then(function(response) {
        var data = response.data;
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .catch(function(response) {
        $scope.response = response.data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

};
