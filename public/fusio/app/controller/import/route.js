'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, route) {

  $scope.route = route;

  $scope.statuuus = [{
    key: 4,
    value: "Development"
  }, {
    key: 1,
    value: "Production"
  }, {
    key: 2,
    value: "Deprecated"
  }, {
    key: 3,
    value: "Closed"
  }];

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

};
