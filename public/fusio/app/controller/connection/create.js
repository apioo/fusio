'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, formBuilder) {

  $scope.connection = {
    name: '',
    class: '',
    config: {}
  };
  $scope.elements = [];
  $scope.config = {};
  $scope.connections = [];

  $scope.create = function(connection) {
    var data = angular.copy(connection);

    if (angular.isObject(data.config)) {
      data.config = formBuilder.postProcessModel($scope.config, $scope.elements);
    }

    $http.post(fusio.baseUrl + 'backend/connection', data)
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

  $http.get(fusio.baseUrl + 'backend/connection/list')
    .then(function(response) {
      var data = response.data;
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

};
