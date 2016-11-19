'use strict';

angular.module('fusioApp.scope', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/scope', {
    templateUrl: 'app/scope/index.html',
    controller: 'ScopeCtrl'
  });
}])

.controller('ScopeCtrl', ['$scope', '$http', '$uibModal', 'fusio', function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/scope?search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.scopes = data.entry;
    });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/scope?startIndex=' + startIndex + '&search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.scopes = data.entry;
    });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/scope?search=' + encodeURIComponent(search)).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.scopes = data.entry;
    });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/scope/create.html',
      controller: 'ScopeCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(scope) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/scope/update.html',
      controller: 'ScopeUpdateCtrl',
      resolve: {
        scope: function() {
          return scope;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(scope) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/scope/delete.html',
      controller: 'ScopeDeleteCtrl',
      resolve: {
        scope: function() {
          return scope;
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

.controller('ScopeCreateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', function($scope, $http, $uibModalInstance, fusio) {

  $scope.scope = {
    name: ''
  };

  $scope.routes = [];

  $http.get(fusio.baseUrl + 'backend/routes?count=1024').success(function(data) {
    $scope.routes = data.entry;
  });

  $scope.create = function(scope) {
    var data = angular.copy(scope);

    var routes = [];
    for (var i = 0; i < $scope.routes.length; i++) {
      var methods = [];
      if ($scope.routes[i].allowedMethods) {
        for (var key in $scope.routes[i].allowedMethods) {
          if ($scope.routes[i].allowedMethods[key] === true) {
            methods.push(key.toUpperCase());
          }
        }
      }

      if (methods.length > 0) {
        routes.push({
          routeId: $scope.routes[i].id,
          allow: true,
          methods: methods.join('|')
        });
      }
    }

    data.routes = routes;

    $http.post(fusio.baseUrl + 'backend/scope', data)
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

.controller('ScopeUpdateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'scope', function($scope, $http, $uibModalInstance, fusio, scope) {

  $scope.scope = scope;

  $scope.routes = [];

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes?count=1024').success(function(data) {
      var routes = [];
      for (var i = 0; i < data.entry.length; i++) {
        var route = data.entry[i];
        if ($scope.scope.routes) {
          for (var j = 0; j < $scope.scope.routes.length; j++) {
            if ($scope.scope.routes[j].routeId == route.id) {
              var methods = [];
              if ($scope.scope.routes[j].methods) {
                methods = $scope.scope.routes[j].methods.split('|');
              }
              var allowedMethods = {};
              for (var k = 0; k < methods.length; k++) {
                allowedMethods[methods[k].toLowerCase()] = true;
              }

              route.allow = $scope.scope.routes[j].allow ? true : false;
              route.allowedMethods = allowedMethods;
            }
          }
        }
        routes.push(route);
      }
      $scope.routes = routes;
    });
  };

  $scope.update = function(scope) {
    var data = angular.copy(scope);

    var routes = [];
    if ($scope.routes) {
      for (var i = 0; i < $scope.routes.length; i++) {
        var methods = [];
        if ($scope.routes[i].allowedMethods) {
          for (var key in $scope.routes[i].allowedMethods) {
            if ($scope.routes[i].allowedMethods[key] === true) {
              methods.push(key.toUpperCase());
            }
          }
        }

        if (methods.length > 0) {
          routes.push({
            routeId: $scope.routes[i].id,
            allow: true,
            methods: methods.join('|')
          });
        }
      }
    }

    data.routes = routes;

    $http.put(fusio.baseUrl + 'backend/scope/' + scope.id, data)
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

  $http.get(fusio.baseUrl + 'backend/scope/' + scope.id)
    .success(function(data) {
      $scope.scope = data;

      $scope.loadRoutes();
    });

}])

.controller('ScopeDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'scope', function($scope, $http, $uibModalInstance, fusio, scope) {

  $scope.scope = scope;

  $scope.delete = function(scope) {
    $http.delete(fusio.baseUrl + 'backend/scope/' + scope.id)
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

