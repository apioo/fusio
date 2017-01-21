'use strict';

module.exports = function($scope, $http, $uibModal, $uibModalInstance, fusio, log) {

  $scope.log = log;

  $scope.statusLineOpen = true;
  $scope.headerOpen = true;
  $scope.bodyOpen = false;
  $scope.errorsOpen = false;

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.openTraceDialog = function(error) {
    $uibModal.open({
      size: 'md',
      backdrop: 'static',
      template: '<div class="modal-body"><pre>' + error.trace + '</pre></div>'
    });
  };

  $http.get(fusio.baseUrl + 'backend/log/' + log.id)
    .then(function(response) {
      $scope.log = response.data;
    });

};
