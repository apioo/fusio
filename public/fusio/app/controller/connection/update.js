'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, formBuilder, connection) {

  $scope.connection = connection;
  $scope.elements = [];
  $scope.config = {};
  $scope.connections = [];

  $scope.update = function(connection) {
    var data = angular.copy(connection);

    // cast every config value to string
    if (angular.isObject(data.config)) {
      data.config = formBuilder.postProcessModel($scope.config, $scope.elements);
    }

    $http.put(fusio.baseUrl + 'backend/connection/' + connection.id, data)
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

  $scope.loadConfig = function() {
    if ($scope.connection.class) {
      $http.get(fusio.baseUrl + 'backend/connection/form?class=' + encodeURIComponent($scope.connection.class))
        .then(function(response) {
          var data = response.data;
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          $scope.elements = data.element;
          $scope.config = formBuilder.preProcessModel($scope.connection.config, $scope.elements);
          var linkFn = formBuilder.buildHtml($scope.elements, 'config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

  $http.get(fusio.baseUrl + 'backend/connection/' + connection.id)
    .then(function(response) {
      $scope.connection = response.data;
      $scope.loadConfig();
    });

};
