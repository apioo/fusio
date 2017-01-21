'use strict';

module.exports = function($scope, $http, $uibModal, fusio) {

  // set initial date range
  var fromDate = new Date();
  fromDate.setDate(fromDate.getDate() - 9);
  var toDate = new Date();

  var query = '?from=' + fromDate.toISOString() + '&to=' + toDate.toISOString();

  $http.get(fusio.baseUrl + 'backend/statistic/incoming_requests' + query)
    .then(function(response) {
      $scope.incomingRequests = response.data;
    });

  $http.get(fusio.baseUrl + 'backend/statistic/most_used_routes' + query)
    .then(function(response) {
      $scope.mostUsedRoutes = response.data;
    });

  $http.get(fusio.baseUrl + 'backend/statistic/most_used_apps' + query)
    .then(function(response) {
      $scope.mostUsedApps = response.data;
    });

  $http.get(fusio.baseUrl + 'backend/dashboard/latest_requests')
    .then(function(response) {
      $scope.latestRequests = response.data.entry;
    });

  $http.get(fusio.baseUrl + 'backend/dashboard/latest_apps')
    .then(function(response) {
      $scope.latestApps = response.data.entry;
    });

  $http.get(fusio.baseUrl + 'backend/statistic/errors_per_route' + query)
    .then(function(response) {
      $scope.errorsPerRoute = response.data;
    });

};
