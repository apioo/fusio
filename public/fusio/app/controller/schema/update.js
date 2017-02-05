'use strict';

module.exports = function($scope, $http, $uibModalInstance, $uibModal, fusio, schema) {

  $scope.schema = schema;

  $scope.update = function(schema) {
    var data = angular.copy(schema);

    // convert string to json
    if (angular.isString(data.source)) {
      data.source = JSON.parse(data.source);
    }

    $http.put(fusio.baseUrl + 'backend/schema/' + schema.id, data)
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

  $http.get(fusio.baseUrl + 'backend/schema/' + schema.id)
    .then(function(response) {
      var data = response.data;
      if (!angular.isString(data.source)) {
        data.source = JSON.stringify(data.source, null, 4);
      }

      $scope.schema = data;
    });

};
