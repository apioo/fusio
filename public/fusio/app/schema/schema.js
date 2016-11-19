'use strict';

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/schema', {
    templateUrl: 'app/schema/index.html',
    controller: 'SchemaCtrl'
  });
}])

.controller('SchemaCtrl', ['$scope', '$http', '$uibModal', '$routeParams', '$location', 'fusio', function($scope, $http, $uibModal, $routeParams, $location, fusio) {

  $scope.response = null;
  $scope.search = '';
  $scope.routes = [];
  $scope.routeId = $routeParams.routeId ? parseInt($routeParams.routeId) : null;

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/schema?search=' + search + '&routeId=' + routeId).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.schemas = data.entry;
    });
  };

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes').success(function(data) {
      $scope.routes = data.entry;
    });
  };

  $scope.changeRoute = function() {
    $location.search('routeId', $scope.routeId);
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/schema?startIndex=' + startIndex + '&search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.schemas = data.entry;
    });
  };

  $scope.doSearch = function(search) {
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/schema?search=' + encodeURIComponent(search) + '&routeId=' + routeId).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.schemas = data.entry;
    });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/schema/create.html',
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
      templateUrl: 'app/schema/update.html',
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
      templateUrl: 'app/schema/delete.html',
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

}])

.controller('SchemaCreateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', function($scope, $http, $uibModalInstance, fusio) {

  $scope.schema = {
    name: '',
    source: ''
  };

  $scope.create = function(schema) {
    var data = angular.copy(schema);

    // convert string to json
    if (angular.isString(data.source)) {
      data.source = JSON.parse(data.source);
    }

    $http.post(fusio.baseUrl + 'backend/schema', data)
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

}])

.controller('SchemaUpdateCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'fusio', 'schema', function($scope, $http, $uibModalInstance, $uibModal, fusio, schema) {

  $scope.schema = schema;

  $scope.update = function(schema) {
    var data = angular.copy(schema);

    // convert string to json
    if (angular.isString(data.source)) {
      data.source = JSON.parse(data.source);
    }

    $http.put(fusio.baseUrl + 'backend/schema/' + schema.id, data)
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

  $scope.preview = function(schema) {
    var data = angular.copy(schema);
    if (typeof data.source == 'string') {
      data.source = JSON.parse(data.source);
    }

    $http.put(fusio.baseUrl + 'backend/schema/' + data.id, data)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/schema/preview.html',
            controller: 'SchemaPreviewCtrl',
            resolve: {
              schema: function() {
                return schema;
              }
            }
          });

          modalInstance.result.then(function(response) {
          }, function() {
          });
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $http.get(fusio.baseUrl + 'backend/schema/' + schema.id)
    .success(function(data) {
      if (!angular.isString(data.source)) {
        data.source = JSON.stringify(data.source, null, 4);
      }

      $scope.schema = data;
    });

}])

.controller('SchemaDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'schema', function($scope, $http, $uibModalInstance, fusio, schema) {

  $scope.schema = schema;

  $scope.delete = function(schema) {
    $http.delete(fusio.baseUrl + 'backend/schema/' + schema.id)
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

}])

.controller('SchemaPreviewCtrl', ['$scope', '$http', '$uibModalInstance', 'schema', 'fusio', function($scope, $http, $uibModalInstance, schema, fusio) {

  $scope.schema = schema;
  $scope.response = null;

  $scope.preview = function(schema) {
    $http.post(fusio.baseUrl + 'backend/schema/preview/' + schema.id, null)
      .success(function(data) {
        $scope.response = data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.preview(schema);

}]);
