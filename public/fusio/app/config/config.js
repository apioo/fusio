'use strict';

angular.module('fusioApp.config', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/config', {
    templateUrl: 'app/config/index.html',
    controller: 'ConfigCtrl'
  });
}])

.controller('ConfigCtrl', ['$scope', '$http', '$uibModal', 'fusio', function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/config?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.configs = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/config?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.configs = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/config?search=' + encodeURIComponent(search))
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.configs = data.entry;
      });
  };

  $scope.openUpdateDialog = function(config) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/config/update.html',
      controller: 'ConfigUpdateCtrl',
      resolve: {
        config: function() {
          return config;
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

}])

.controller('ConfigUpdateCtrl', ['$scope', '$http', '$uibModalInstance', 'config', 'fusio', function($scope, $http, $uibModalInstance, config, fusio) {

  var data = angular.copy(config);
  if (data.type == 2) {
    data.value = data.value == '1';
  } else if (data.type == 3) {
    data.value = parseInt(data.value);
  }

  $scope.config = data;

  $scope.update = function(config) {
    // value must be always a string
    var data = angular.copy(config);
    if (data.type == 2) {
      data.value = data.value ? '1' : '0';
    } else {
      data.value = '' + data.value;
    }

    $http.put(fusio.baseUrl + 'backend/config/' + data.id, data)
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

}]);
