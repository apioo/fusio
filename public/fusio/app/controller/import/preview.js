'use strict';

module.exports = function($scope, $http, $uibModalInstance, $uibModal, fusio, data) {

  $scope.data = data;

  $scope.doProcess = function() {
    $http.post(fusio.baseUrl + 'backend/import/process', data)
      .then(function(response) {
        var data = response.data;
        if ('success' in data && data.success === false) {
          $scope.error = data.message;
          return;
        } else {
          $scope.error = null;
        }

        $uibModalInstance.close();
      })
      .catch(function(response) {
        var data = response.data;
        if ('success' in data && data.success === false) {
          $scope.error = data.message;
        } else {
          $scope.error = 'An unknown error occured';
        }
      });
  };

  $scope.openRouteDialog = function(route) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/import/route.html',
      controller: 'ImportRouteCtrl',
      resolve: {
        route: function() {
          return route;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.openActionDialog = function(action) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/import/action.html',
      controller: 'ImportActionCtrl',
      resolve: {
        action: function() {
          return action;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.openSchemaDialog = function(schema) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/import/schema.html',
      controller: 'ImportSchemaCtrl',
      resolve: {
        schema: function() {
          return schema;
        }
      }
    });

    modalInstance.result.then(function(response) {
    }, function() {
    });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

};
