'use strict';

module.exports = function($scope, $http, $uibModalInstance, action, fusio) {

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

};
