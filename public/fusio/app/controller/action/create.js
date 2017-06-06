'use strict';

module.exports = function($scope, $http, $uibModalInstance, formBuilder, fusio) {

  $scope.action = {
    name: "",
    class: "",
    engine: "Fusio\\Engine\\Factory\\Resolver\\PhpClass",
    config: {}
  };
  $scope.elements = [];
  $scope.config = {};
  $scope.actions = [];

  $scope.create = function(action) {
    var data = angular.copy(action);

    if (angular.isObject($scope.config)) {
      data.config = formBuilder.postProcessModel($scope.config, $scope.elements);
    }

    $http.post(fusio.baseUrl + 'backend/action', data)
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

  $http.get(fusio.baseUrl + 'backend/action/list')
    .then(function(response) {
      var data = response.data;
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
        .then(function(response) {
          var data = response.data;
          var containerEl = angular.element(document.querySelector('#config-form'));
          containerEl.children().remove();

          $scope.elements = data.element;
          $scope.config = formBuilder.preProcessModel($scope.action.config, $scope.elements);
          var linkFn = formBuilder.buildHtml($scope.elements, 'config');
          if (angular.isFunction(linkFn)) {
            var el = linkFn($scope);
            containerEl.append(el);
          }
        });
    }
  };

};
