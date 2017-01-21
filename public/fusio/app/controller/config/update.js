'use strict';

module.exports = function($scope, $http, $uibModalInstance, config, fusio) {

  var data = angular.copy(config);
  if (data.type == 2) {
    data.value = data.value == '1';
  } else if (data.type == 3) {
    data.value = parseInt(data.value);
  }

  $scope.config = data;

  $scope.update = function(config) {
    // value must be always a string
    var data = angular.copy(config);
    if (data.type == 2) {
      data.value = data.value ? '1' : '0';
    } else {
      data.value = '' + data.value;
    }

    $http.put(fusio.baseUrl + 'backend/config/' + data.id, data)
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
