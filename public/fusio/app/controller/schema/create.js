'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio) {

  $scope.schema = {
    name: '',
    source: ''
  };

  $scope.create = function(schema) {
    var data = angular.copy(schema);

    // convert string to json
    if (angular.isString(data.source)) {
      data.source = JSON.parse(data.source);
    }

    $http.post(fusio.baseUrl + 'backend/schema', data)
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
