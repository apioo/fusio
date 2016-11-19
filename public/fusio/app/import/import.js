'use strict';

angular.module('fusioApp.import', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/import', {
    templateUrl: 'app/import/index.html',
    controller: 'ImportCtrl'
  });
}])

.controller('ImportCtrl', ['$scope', '$http', '$uibModal', 'fusio', function($scope, $http, $uibModal, fusio) {

  $scope.source = null;
  $scope.error = null;
  $scope.success = false;

  $scope.transform = function(source, format) {
    $http.post(fusio.baseUrl + 'backend/import/' + format, {schema: source}).success(function(data) {
      if ('success' in data && data.success === false) {
        $scope.error = data.message;
        return;
      } else {
        $scope.error = null;
      }

      $scope.openPreviewDialog(data);
    }).error(function(data) {
      if ('success' in data && data.success === false) {
        $scope.error = data.message;
      } else {
        $scope.error = 'An unknown error occured';
      }
    });
  };

  $scope.openPreviewDialog = function(data) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/import/preview.html',
      controller: 'ImportPreviewCtrl',
      resolve: {
        data: function() {
          return data;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.success = true;
    }, function() {
    });
  };

}])

.controller('ImportPreviewCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'fusio', 'data', function($scope, $http, $uibModalInstance, $uibModal, fusio, data) {

  $scope.data = data;

  $scope.doProcess = function() {
    $http.post(fusio.baseUrl + 'backend/import/process', data).success(function(data) {
      if ('success' in data && data.success === false) {
        $scope.error = data.message;
        return;
      } else {
        $scope.error = null;
      }

      $uibModalInstance.close();
    }).error(function(data) {
      if ('success' in data && data.success === false) {
        $scope.error = data.message;
      } else {
        $scope.error = 'An unknown error occured';
      }
    });
  };

  $scope.openRouteDialog = function(route) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/import/route.html',
      controller: 'ImportRouteCtrl',
      resolve: {
        route: function() {
          return route;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.openActionDialog = function(action) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/import/action.html',
      controller: 'ImportActionCtrl',
      resolve: {
        action: function() {
          return action;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.openSchemaDialog = function(schema) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/import/schema.html',
      controller: 'ImportSchemaCtrl',
      resolve: {
        schema: function() {
          return schema;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

}])

.controller('ImportRouteCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'route', function($scope, $http, $uibModalInstance, fusio, route) {

  $scope.route = route;

  $scope.statuuus = [{
    key: 4,
    value: "Development"
  }, {
    key: 1,
    value: "Production"
  }, {
    key: 2,
    value: "Deprecated"
  }, {
    key: 3,
    value: "Closed"
  }];

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

}])

.controller('ImportActionCtrl', ['$scope', '$http', '$uibModalInstance', 'action', function($scope, $http, $uibModalInstance, action) {

  $scope.action = angular.copy(action);

  $scope.close = function() {
    action.name = $scope.action.name;

    if (angular.isObject($scope.action.config)) {
      action.config = $scope.action.config;
    }

    $uibModalInstance.dismiss('cancel');
  };

}])

.controller('ImportSchemaCtrl', ['$scope', '$http', '$uibModalInstance', 'schema', function($scope, $http, $uibModalInstance, schema) {

  var copySchema = angular.copy(schema);

  if (angular.isObject(copySchema.source)) {
    copySchema.source = JSON.stringify(copySchema.source, null, 4);
  }

  $scope.schema = copySchema;

  $scope.close = function() {
    schema.name = $scope.schema.name;

    if (angular.isString($scope.schema.source)) {
      schema.source = JSON.parse($scope.schema.source);
    }

    $uibModalInstance.dismiss('cancel');
  };

}]);

