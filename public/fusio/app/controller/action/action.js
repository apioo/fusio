'use strict';

module.exports = function($scope, $http, $uibModal, $routeParams, $location, fusio) {

  $scope.response = null;
  $scope.search = '';
  $scope.routes = [];
  $scope.routeId = $routeParams.routeId ? parseInt($routeParams.routeId) : null;

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/action?search=' + search + '&routeId=' + routeId)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.actions = data.entry;
      });
  };

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes')
      .then(function(response) {
        var data = response.data;
        $scope.routes = data.entry;
      });
  };

  $scope.changeRoute = function() {
    $location.search('routeId', $scope.routeId);
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/action?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.actions = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/action?search=' + encodeURIComponent(search) + '&routeId=' + routeId)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.actions = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/action/create.html',
      controller: 'ActionCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(action) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/action/update.html',
      controller: 'ActionUpdateCtrl',
      resolve: {
        action: function() {
          return action;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(action) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/action/delete.html',
      controller: 'ActionDeleteCtrl',
      resolve: {
        action: function() {
          return action;
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
