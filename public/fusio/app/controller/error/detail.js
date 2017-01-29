'use strict';

module.exports = function($scope, $http, $uibModal, $uibModalInstance, fusio, error) {

  $scope.error = error;

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $http.get(fusio.baseUrl + 'backend/log/error/' + error.id)
    .then(function(response) {
      $scope.error = response.data;
    });

};
