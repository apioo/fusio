'use strict';

angular.module('fusioApp.action', ['ngRoute', 'ui.ace'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/action', {
    templateUrl: 'app/action/index.html',
    controller: 'ActionCtrl'
  });
}])

.controller('ActionCtrl', ['$scope', '$http', '$uibModal', '$routeParams', '$location', 'fusio', function($scope, $http, $uibModal, $routeParams, $location, fusio) {

  $scope.response = null;
  $scope.search = '';
  $scope.routes = [];
  $scope.routeId = $routeParams.routeId ? parseInt($routeParams.routeId) : null;

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/action?search=' + search + '&routeId=' + routeId)
      .then(function(data) {
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.actions = data.entry;
      });
  };

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes')
      .then(function(data) {
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
      .then(function(data) {
        $scope.totalResults = data.totalResults;
        $scope.actions = data.entry;
      });
  };

  $scope.doSearch = function(search) {
    var routeId = $scope.routeId;

    $http.get(fusio.baseUrl + 'backend/action?search=' + encodeURIComponent(search) + '&routeId=' + routeId)
      .then(function(data) {
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.actions = data.entry;
      });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/action/create.html',
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
      templateUrl: 'app/action/update.html',
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
      templateUrl: 'app/action/delete.html',
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

}])

.controller('ActionCreateCtrl', ['$scope', '$http', '$uibModalInstance', 'formBuilder', 'fusio', function($scope, $http, $uibModalInstance, formBuilder, fusio) {

  $scope.action = {
    name: "",
    class: "",
    config: {}
  };
  $scope.actions = [];

  $scope.create = function(action) {
    var data = angular.copy(action);

    // cast every config value to string
    if (angular.isObject(data.config)) {
      var config = {};
      for (var key in data.config) {
        if (data.config.hasOwnProperty(key)) {
          config[key] = '' + data.config[key];
        }
      }
      data.config = config;
    }

    $http.post(fusio.baseUrl + 'backend/action', data)
      .then(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .catch(function(data) {
        $scope.response = data;
      });
  };

  $http.get(fusio.baseUrl + 'backend/action/list')
    .then(function(data) {
      $scope.actions = data.actions;

      if (data.actions[0]) {
        $scope.action.class = data.actions[0].class;
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
    if ($scope.action.class) {
      $http.get(fusio.baseUrl + 'backend/action/form?class=' + encodeURIComponent($scope.action.class))
        .then(function(data) {
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          var linkFn = formBuilder.buildHtml(data.element, 'action.config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

}])

.controller('ActionUpdateCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'action', 'formBuilder', '$timeout', 'fusio', function($scope, $http, $uibModalInstance, $uibModal, action, formBuilder, $timeout, fusio) {

  $scope.action = action;
  $scope.actions = [];

  $scope.update = function(action) {
    var data = angular.copy(action);

    // cast every config value to string
    if (angular.isObject(data.config)) {
      var config = {};
      for (var key in data.config) {
        if (data.config.hasOwnProperty(key)) {
          config[key] = '' + data.config[key];
        }
      }
      data.config = config;
    }

    $http.put(fusio.baseUrl + 'backend/action/' + action.id, data)
      .then(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .catch(function(data) {
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
    if ($scope.action.class) {
      $http.get(fusio.baseUrl + 'backend/action/form?class=' + encodeURIComponent($scope.action.class))
        .then(function(data) {
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          var linkFn = formBuilder.buildHtml(data.element, 'action.config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

  $scope.execute = function(action) {
    $http.put(fusio.baseUrl + 'backend/action/' + action.id, action)
      .then(function(data) {
        $scope.response = data;
        if (data.success === true) {
          var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/action/execute.html',
            controller: 'ActionExecuteCtrl',
            resolve: {
              action: function() {
                return action;
              }
            }
          });

          modalInstance.result.then(function(response) {
          }, function() {
          });
        }
      })
      .catch(function(data) {
        $scope.response = data;
      });
  };

  $http.get(fusio.baseUrl + 'backend/action/' + action.id)
    .then(function(data) {
      if (angular.isArray(data.config)) {
        data.config = {};
      }

      $scope.action = data;

      $scope.loadConfig();
    });

}])

.controller('ActionDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'action', 'fusio', function($scope, $http, $uibModalInstance, action, fusio) {

  $scope.action = action;

  $scope.delete = function(action) {
    $http.delete(fusio.baseUrl + 'backend/action/' + action.id)
      .then(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .catch(function(data) {
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

.controller('ActionExecuteCtrl', ['$scope', '$http', '$uibModalInstance', 'action', 'fusio', function($scope, $http, $uibModalInstance, action, fusio) {

  $scope.action = action;
  $scope.methods = ['GET', 'POST', 'PUT', 'DELETE'];
  $scope.request = {
    method: 'GET',
    uriFragments: '',
    parameters: '',
    body: '{}'
  };
  $scope.response = null;

  $scope.requestOpen = false;
  $scope.responseOpen = true;

  $scope.execute = function(action, request) {
    var body = JSON.parse(request.body);
    if (!angular.isObject(body)) {
      body = {};
    }
    var data = {
      method: request.method,
      uriFragments: request.uriFragments,
      parameters: request.parameters,
      body: body
    };

    $http.post(fusio.baseUrl + 'backend/action/execute/' + action.id, data)
      .then(function(data) {
        // in case we have no body property we have probably a general error
        // message in this case we simply show the complete response as body
        var resp = {};
        if (!data.body) {
          resp.statusCode = 500;
          resp.headers = {};
          resp.body = data;
        } else {
          resp = data;
        }

        $scope.response = {
          statusCode: resp.statusCode,
          headers: resp.headers,
          body: JSON.stringify(resp.body, null, 4)
        };
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.execute(action, $scope.request);

}]);
