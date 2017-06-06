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

    // if we have a config we must update the action first else we can directly 
    // execute the action
    if (angular.isObject(data.config) && !angular.equals(data.config, {})) {
      $http.put(fusio.baseUrl + 'backend/action/' + action.id, data)
        .then(function(response) {
          var data = response.data;
          if (data.success === true) {
            $scope.execute(action, request);
          }
        })
        .catch(function(response) {
        });
    } else {
      $scope.execute(action, request);
    }
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
            $scope.adjustEditorHeight();
          }
        });
    }
  };

  $scope.adjustEditorHeight = function(){
    var blockCount = 3;
    var blockUsed = 0;
    var formEditor = false;
    var formElements = document.querySelectorAll('#config-form .form-group');

    for (var i = 0; i < formElements.length; ++i) {
      var aceEditor = formElements[i].querySelector('.ace_editor');
      if (aceEditor) {
        if (formEditor) {
          // in case we have multiple ace editors we skip this magic
          formEditor = false;
          break;
        }
        formEditor = aceEditor;
      } else {
        blockUsed++;
      }
    }

    if (formEditor) {
      var baseHeight = 133;
      var blockFree = blockCount - blockUsed;
      if (blockFree > 0) {
        var editorEl = angular.element(formEditor);
        editorEl.css('height', (baseHeight + (blockFree * 99)) + 'px');
      }
    }
  };

  $http.get(fusio.baseUrl + 'backend/action/' + $routeParams.action_id)
    .then(function(response) {
      $scope.action = response.data;
      $scope.loadConfig();
    });

};
