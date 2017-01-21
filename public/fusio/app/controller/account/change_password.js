'use strict';

module.exports = function($scope, $http, fusio) {

  $scope.account = {
    oldPassword: "",
    newPassword: "",
    verifyPassword: ""
  };

  $scope.updatePassword = function() {
    $http.put(fusio.baseUrl + 'backend/account/change_password', $scope.account)
      .then(function(response) {
        $scope.response = response.data;
      })
      .catch(function(response) {
        $scope.response = response.data;
      });
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

};
