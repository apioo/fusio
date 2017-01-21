'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, schema) {

  $scope.schema = schema;

  $scope.delete = function(schema) {
    $http.delete(fusio.baseUrl + 'backend/schema/' + schema.id)
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
