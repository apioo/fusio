'use strict';

module.exports = function($scope, $http, $uibModal, $uibModalInstance, fusio, token) {

  $scope.token = token;

  $scope.statuuus = [{
    key: 1,
    value: "Active"
  }, {
    key: 2,
    value: "Deleted"
  }];

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $http.get(fusio.baseUrl + 'backend/app/token/' + token.id)
    .then(function(response) {
      $scope.token = response.data;
    });

};
