'use strict';

angular.module('fusioApp.connection', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/connection', {
    templateUrl: 'app/connection/index.html',
    controller: 'ConnectionCtrl'
  });
}])

.controller('ConnectionCtrl', ['$scope', '$http', '$uibModal', 'fusio', function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/connection?search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.connections = data.entry;
    });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/connection?startIndex=' + startIndex + '&search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.connections = data.entry;
    });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/connection?search=' + encodeURIComponent(search)).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.connections = data.entry;
    });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/connection/create.html',
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
      templateUrl: 'app/connection/update.html',
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
      templateUrl: 'app/connection/delete.html',
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

}])

.controller('ConnectionCreateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'formBuilder', function($scope, $http, $uibModalInstance, fusio, formBuilder) {

  $scope.connection = {
    name: '',
    class: '',
    config: {}
  };

  $scope.connections = [];

  $scope.create = function(connection) {
    $http.post(fusio.baseUrl + 'backend/connection', connection)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $http.get(fusio.baseUrl + 'backend/connection/list')
    .success(function(data) {
      $scope.connections = data.connections;

      if (data.connections[0]) {
        $scope.connection.class = data.connections[0].class;
        $scope.loadConfig();
      }
    });

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

  $scope.loadConfig = function() {
    if ($scope.connection.class) {
      $http.get(fusio.baseUrl + 'backend/connection/form?class=' + encodeURIComponent($scope.connection.class))
        .success(function(data) {
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          var linkFn = formBuilder.buildHtml(data.element, 'connection.config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

}])

.controller('ConnectionUpdateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'formBuilder', 'connection', function($scope, $http, $uibModalInstance, fusio, formBuilder, connection) {

  if (angular.isArray(connection.config)) {
    connection.config = {};
  }

  $scope.connection = connection;
  $scope.connections = [];

  $scope.update = function(connection) {
    $http.put(fusio.baseUrl + 'backend/connection/' + connection.id, connection)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

  $scope.loadConfig = function() {
    if ($scope.connection.class) {
      $http.get(fusio.baseUrl + 'backend/connection/form?class=' + encodeURIComponent($scope.connection.class))
        .success(function(data) {
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          var linkFn = formBuilder.buildHtml(data.element, 'connection.config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

  $http.get(fusio.baseUrl + 'backend/connection/' + connection.id)
    .success(function(data) {
      $scope.connection = data;

      $scope.loadConfig();
    });

}])

.controller('ConnectionDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'connection', function($scope, $http, $uibModalInstance, fusio, connection) {

  $scope.connection = connection;

  $scope.delete = function(connection) {
    $http.delete(fusio.baseUrl + 'backend/connection/' + connection.id)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

}]);

