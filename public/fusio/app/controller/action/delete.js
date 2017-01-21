'use strict';

module.exports = function($scope, $http, $uibModalInstance, action, fusio) {

  $scope.action = action;

  $scope.delete = function(action) {
    $http.delete(fusio.baseUrl + 'backend/action/' + action.id)
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
