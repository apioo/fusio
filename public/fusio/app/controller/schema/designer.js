'use strict';

module.exports = function($scope, $http, $routeParams, fusio) {

  $scope.schema = {};
  $scope.response = null;

  $scope.update = function(schema) {
    var data = angular.copy(schema);
    if (typeof data.source == 'string') {
      data.source = JSON.parse(data.source);
    }

    $http.put(fusio.baseUrl + 'backend/schema/' + data.id, data)
      .then(function(response) {
        var data = response.data;
        if (data.success === true) {
          $scope.loadPreview(schema.id);
        }
      })
      .catch(function(response) {
      });
  };

  $scope.loadPreview = function(schemaId){
    $http.post(fusio.baseUrl + 'backend/schema/preview/' + schemaId, null)
      .then(function(response) {
        var data = response.data;
        data.preview = data.preview.replace(/href=\"\#([A-z0-9_]+)\"/g, "href=\"#/schema/designer/" + schemaId + "\"");
        $scope.response = data;
      });
  };

  $http.get(fusio.baseUrl + 'backend/schema/' + $routeParams.schema_id)
    .then(function(response) {
      var data = response.data;
      if (!angular.isString(data.source)) {
        data.source = JSON.stringify(data.source, null, 4);
      }

      $scope.schema = data;
    });

  $scope.loadPreview($routeParams.schema_id);

};
