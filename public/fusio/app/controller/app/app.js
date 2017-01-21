'use strict';

module.exports = function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/app?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.apps = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/app?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.apps = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/app?search=' + encodeURIComponent(search))
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.apps = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/app/create.html',
      controller: 'AppCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(app) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/app/update.html',
      controller: 'AppUpdateCtrl',
      resolve: {
        app: function() {
          return app;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(app) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/app/delete.html',
      controller: 'AppDeleteCtrl',
      resolve: {
        app: function() {
          return app;
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
