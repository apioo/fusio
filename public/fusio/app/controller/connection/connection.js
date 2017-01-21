'use strict';

module.exports = function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/connection?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.connections = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/connection?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.connections = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/connection?search=' + encodeURIComponent(search))
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.connections = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/connection/create.html',
      controller: 'ConnectionCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(connection) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/connection/update.html',
      controller: 'ConnectionUpdateCtrl',
      resolve: {
        connection: function() {
          return connection;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(connection) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/connection/delete.html',
      controller: 'ConnectionDeleteCtrl',
      resolve: {
        connection: function() {
          return connection;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

  $scope.load();

};
