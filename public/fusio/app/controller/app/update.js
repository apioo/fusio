'use strict';

module.exports = function($scope, $http, $uibModalInstance, app, fusio) {

  $scope.app = app;

  $scope.states = [{
    key: 1,
    value: 'Active'
  }, {
    key: 2,
    value: 'Pending'
  }, {
    key: 3,
    value: 'Deactivated'
  }];

  $scope.update = function(app) {
    var data = angular.copy(app);

    // remove tokens
    if (data.tokens) {
      delete data.tokens;
    }

    // remove null values from scope
    if (angular.isArray(data.scopes)) {
      data.scopes = data.scopes.filter(function(val) {
        return val !== null;
      });
    }

    $http.put(fusio.baseUrl + 'backend/app/' + app.id, data)
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

  $scope.loadApp = function() {
    $http.get(fusio.baseUrl + 'backend/app/' + app.id)
      .then(function(response) {
        var data = response.data;
        var scopes = [];
        if (angular.isArray(data.scopes)) {
          for (var i = 0; i < $scope.scopes.length; i++) {
            var found = null;
            for (var j = 0; j < data.scopes.length; j++) {
              if ($scope.scopes[i].name == data.scopes[j]) {
                found = $scope.scopes[i].name;
                break;
              }
            }
            scopes.push(found);
          }
        }
        data.scopes = scopes;

        $scope.app = data;
      });
  };

  $scope.removeToken = function(token) {
    $http.delete(fusio.baseUrl + 'backend/app/' + app.id + '/token/' + token.id)
      .then(function(response) {
        if ($scope.app.tokens) {
          var tokens = [];
          for (var i = 0; i < $scope.app.tokens.length; i++) {
            if ($scope.app.tokens[i].id != token.id) {
              tokens.push($scope.app.tokens[i]);
              break;
            }
          }
          $scope.app.tokens = tokens;
        }
      });
  };

  $scope.getScopes = function(){
    $http.get(fusio.baseUrl + 'backend/scope?count=1024')
      .then(function(response) {
        $scope.scopes = response.data.entry;

        $scope.loadApp();
      });
  };

  $scope.getScopes();

};
