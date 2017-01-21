'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio) {

  $scope.scope = {
    name: ''
  };

  $scope.routes = [];

  $http.get(fusio.baseUrl + 'backend/routes?count=1024')
    .then(function(response) {
      $scope.routes = response.data.entry;
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

};
