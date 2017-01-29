'use strict';

module.exports = function($scope, $http, $uibModal, $uibModalInstance, fusio, log) {

  $scope.log = log;

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.openDetailDialog = function(error) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/error/detail.html',
      controller: 'ErrorDetailCtrl',
      resolve: {
        error: function() {
          return error;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $http.get(fusio.baseUrl + 'backend/log/' + log.id)
    .then(function(response) {
      $scope.log = response.data;
    });

};
