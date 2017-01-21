'use strict';

module.exports = function($scope, $http, $uibModalInstance, schema, fusio) {

  $scope.schema = schema;
  $scope.response = null;

  $scope.preview = function(schema) {
    $http.post(fusio.baseUrl + 'backend/schema/preview/' + schema.id, null)
      .then(function(response) {
        $scope.response = response.data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.preview(schema);

};
