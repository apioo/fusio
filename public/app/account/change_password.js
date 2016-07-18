'use strict';

angular.module('fusioApp.account', ['ngRoute'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/account/change_password', {
    templateUrl: 'app/account/change_password.html',
    controller: 'ChangePasswordCtrl'
  });
}])

.controller('ChangePasswordCtrl', ['$scope', '$http', function ($scope, $http) {

  $scope.account = {
    oldPassword: "",
    newPassword: "",
    verifyPassword: ""
  };

  $scope.updatePassword = function () {
    $http.put(fusio_url + 'backend/account/change_password', $scope.account)
      .success(function (data) {
        $scope.response = data;
      })
      .error(function (data) {
        $scope.response = data;
      });
  };

  $scope.closeResponse = function () {
    $scope.response = null;
  };

}]);
