'use strict';

module.exports = function($scope, $http, $uibModal, $routeParams, $location, fusio) {

  $scope.response = null;
  $scope.search = '';
  $scope.routes = [];
  $scope.routeId = $routeParams.routeId ? parseInt($routeParams.routeId) : null;

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/schema?search=' + search + '&routeId=' + routeId)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.schemas = data.entry;
      });
  };

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes')
      .then(function(response) {
        $scope.routes = response.data.entry;
      });
  };

  $scope.changeRoute = function() {
    $location.search('routeId', $scope.routeId);
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/schema?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.schemas = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/schema?search=' + encodeURIComponent(search) + '&routeId=' + routeId)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.schemas = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/schema/create.html',
      controller: 'SchemaCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(schema) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/schema/update.html',
      controller: 'SchemaUpdateCtrl',
      resolve: {
        schema: function() {
          return schema;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(schema) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/schema/delete.html',
      controller: 'SchemaDeleteCtrl',
      resolve: {
        schema: function() {
          return schema;
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
  $scope.loadRoutes();

};
