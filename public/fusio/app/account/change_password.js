'use strict';

angular.module('fusioApp.account', ['ngRoute'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/account/change_password', {
    templateUrl: 'app/account/change_password.html',
    controller: 'ChangePasswordCtrl'
  });
}])

.controller('ChangePasswordCtrl', ['$scope', '$http', 'fusio', function($scope, $http, fusio) {

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

}]);
