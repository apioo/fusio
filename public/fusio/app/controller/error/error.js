'use strict';

module.exports = function($scope, $http, $uibModal, $timeout, fusio) {

  $scope.errors = [];

  $scope.load = function() {
    var search = '';
    if ($scope.search) {
      search = encodeURIComponent($scope.search);
    }

    $http.get(fusio.baseUrl + 'backend/log/error?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.errors = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/log/error?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.errors = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/log/error?search=' + encodeURIComponent(search))
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.errors = data.entry;
      });
  };

  $scope.openDetailDialog = function(error) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/error/detail.html',
      controller: 'ErrorDetailCtrl',
      resolve: {
        error: function() {
          return error;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.load();

};
