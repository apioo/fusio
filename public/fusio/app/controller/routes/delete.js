'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, route) {

  $scope.route = route;

  $scope.delete = function(route) {
    $http.delete(fusio.baseUrl + 'backend/routes/' + route.id)
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
