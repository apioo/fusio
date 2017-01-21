'use strict';

module.exports = function($scope, $http, $uibModalInstance, rate, fusio) {

  $scope.rate = rate;

  $scope.delete = function(rate) {
    $http.delete(fusio.baseUrl + 'backend/rate/' + rate.id)
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
