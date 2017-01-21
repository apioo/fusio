'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, scope) {

  $scope.scope = scope;

  $scope.routes = [];

  $scope.loadRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes?count=1024')
      .then(function(response) {
        var data = response.data;
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

  $http.get(fusio.baseUrl + 'backend/scope/' + scope.id)
    .then(function(response) {
      $scope.scope = response.data;

      $scope.loadRoutes();
    });

};
