'use strict';

module.exports = function($scope, $http, $routeParams, fusio, formBuilder) {

  $scope.action = {};
  $scope.methods = ['GET', 'POST', 'PUT', 'DELETE'];

  $scope.request = {
    method: 'GET',
    uriFragments: '',
    parameters: '',
    body: '{}'
  };
  $scope.response = null;
  $scope.error = null;

  $scope.update = function(action, request) {
    var data = angular.copy(action);

    if (angular.isObject($scope.config)) {
      data.config = formBuilder.postProcessModel($scope.config, $scope.elements);
    }

    $http.put(fusio.baseUrl + 'backend/action/' + action.id, data)
      .then(function(response) {
        var data = response.data;
        if (data.success === true) {
          $scope.execute(action, request);
        }
      })
      .catch(function(response) {
      });
  };

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
      .then(function(response) {
        var data = response.data;
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

        // check whether we have an error response
        if (resp.body.success === false && resp.body.message && resp.body.trace) {
          $scope.error = {
            message: resp.body.message,
            trace: resp.body.trace
          };
        } else {
          $scope.error = null;
        }

        $scope.response = {
          statusCode: resp.statusCode,
          headers: resp.headers,
          body: JSON.stringify(resp.body, null, 4)
        };
      });
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

  $http.get(fusio.baseUrl + 'backend/action/' + $routeParams.action_id)
    .then(function(response) {
      $scope.action = response.data;
      $scope.loadConfig();
    });

};
