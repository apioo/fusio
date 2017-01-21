'use strict';

module.exports = function($scope, $http, $uibModalInstance, action) {

  $scope.action = angular.copy(action);

  $scope.close = function() {
    action.name = $scope.action.name;

    if (angular.isObject($scope.action.config)) {
      action.config = $scope.action.config;
    }

    $uibModalInstance.dismiss('cancel');
  };

};
