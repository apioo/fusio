'use strict';

module.exports = function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/rate?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.rates = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/rate?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.rates = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/rate?search=' + encodeURIComponent(search))
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.rates = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/rate/create.html',
      controller: 'RateCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(rate) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/rate/update.html',
      controller: 'RateUpdateCtrl',
      resolve: {
        rate: function() {
          return rate;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(rate) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/rate/delete.html',
      controller: 'RateDeleteCtrl',
      resolve: {
        rate: function() {
          return rate;
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


