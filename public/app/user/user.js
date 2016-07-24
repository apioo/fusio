'use strict';

angular.module('fusioApp.user', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/user', {
    templateUrl: 'app/user/index.html',
    controller: 'UserCtrl'
  });
}])

.controller('UserCtrl', ['$scope', '$http', '$uibModal', 'fusio', function($scope, $http, $uibModal, fusio) {

  $scope.response = null;
  $scope.search = '';

  $scope.load = function() {
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/user?search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.users = data.entry;
    });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/user?startIndex=' + startIndex + '&search=' + search).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.users = data.entry;
    });
  };

  $scope.doSearch = function(search) {
    $http.get(fusio.baseUrl + 'backend/user?search=' + encodeURIComponent(search)).success(function(data) {
      $scope.totalResults = data.totalResults;
      $scope.startIndex = 0;
      $scope.users = data.entry;
    });
  };

  $scope.openCreateDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/user/create.html',
      controller: 'UserCreateCtrl'
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openUpdateDialog = function(user) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/user/update.html',
      controller: 'UserUpdateCtrl',
      resolve: {
        user: function() {
          return user;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.openDeleteDialog = function(user) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/user/delete.html',
      controller: 'UserDeleteCtrl',
      resolve: {
        user: function() {
          return user;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();
    }, function() {
    });
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

  $scope.load();

}])

.controller('UserCreateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', function($scope, $http, $uibModalInstance, fusio) {

  $scope.user = {
    status: 0,
    name: '',
    email: '',
    scopes: []
  };

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

  $http.get(fusio.baseUrl + 'backend/scope?count=1024').success(function(data) {
    $scope.scopes = data.entry;
  });

  $scope.create = function(user) {
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

    $http.post(fusio.baseUrl + 'backend/user', data)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

}])

.controller('UserUpdateCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'user', function($scope, $http, $uibModalInstance, fusio, user) {

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

  $http.get(fusio.baseUrl + 'backend/scope?count=1024').success(function(data) {
    $scope.scopes = data.entry;

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
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
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
      .success(function(data) {
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

}])

.controller('UserDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'user', function($scope, $http, $uibModalInstance, fusio, user) {

  $scope.user = user;

  $scope.delete = function(user) {
    $http.delete(fusio.baseUrl + 'backend/user/' + user.id)
      .success(function(data) {
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .error(function(data) {
        $scope.response = data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

}]);

