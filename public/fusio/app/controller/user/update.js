'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio, user) {

  $scope.user = user;

  $scope.statuuus = [{
    id: 0,
    name: 'Consumer'
  }, {
    id: 1,
    name: 'Administrator'
  }, {
    id: 2,
    name: 'Disabled'
  }];

  $scope.scopes = [];

  $http.get(fusio.baseUrl + 'backend/scope?count=1024')
    .then(function(response) {
      $scope.scopes = response.data.entry;

      $scope.loadUser();
    });

  $scope.update = function(user) {
    var data = angular.copy(user);

    // remove app data
    if (data.apps) {
      delete data.apps;
    }

    // filter scopes
    if (data.scopes && angular.isArray(data.scopes)) {
      data.scopes = data.scopes.filter(function(value) {
        return value !== null;
      });
    }

    $http.put(fusio.baseUrl + 'backend/user/' + data.id, data)
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

  $scope.loadUser = function() {
    $http.get(fusio.baseUrl + 'backend/user/' + user.id)
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

        $scope.user = data;
      });
  };

};
