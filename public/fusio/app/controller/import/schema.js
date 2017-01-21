'use strict';

module.exports = function($scope, $http, $uibModalInstance, schema) {

  var copySchema = angular.copy(schema);

  if (angular.isObject(copySchema.source)) {
    copySchema.source = JSON.stringify(copySchema.source, null, 4);
  }

  $scope.schema = copySchema;

  $scope.close = function() {
    schema.name = $scope.schema.name;

    if (angular.isString($scope.schema.source)) {
      schema.source = JSON.parse($scope.schema.source);
    }

    $uibModalInstance.dismiss('cancel');
  };

};
