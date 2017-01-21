'use strict';

module.exports = function($scope, $http, $uibModal, fusio) {

  $scope.source = null;
  $scope.error = null;
  $scope.success = false;

  $scope.transform = function(source, format) {
    $http.post(fusio.baseUrl + 'backend/import/' + format, {schema: source})
      .then(function(response) {
        var data = response.data;
        if ('success' in data && data.success === false) {
          $scope.error = data.message;
          return;
        } else {
          $scope.error = null;
        }

        $scope.openPreviewDialog(data);
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

  $scope.openPreviewDialog = function(data) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/import/preview.html',
      controller: 'ImportPreviewCtrl',
      resolve: {
        data: function() {
          return data;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.success = true;
    }, function() {
    });
  };

};
