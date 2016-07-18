'use strict';

angular.module('fusioApp.dashboard', ['ngRoute', 'chart.js'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/dashboard', {
    templateUrl: 'app/dashboard/index.html',
    controller: 'DashboardCtrl'
  });
}])

.controller('DashboardCtrl', ['$scope', '$http', '$uibModal', function ($scope, $http, $uibModal) {

  // set initial date range
  var fromDate = new Date();
  fromDate.setDate(fromDate.getDate() - 9);
  var toDate = new Date();

  var query = '?from=' + fromDate.toISOString() + '&to=' + toDate.toISOString();

  $http.get(fusio_url + 'backend/statistic/incoming_requests' + query).success(function (data) {
    $scope.incomingRequests = data;
  });

  $http.get(fusio_url + 'backend/statistic/most_used_routes' + query).success(function (data) {
    $scope.mostUsedRoutes = data;
  });

  $http.get(fusio_url + 'backend/statistic/most_used_apps' + query).success(function (data) {
    $scope.mostUsedApps = data;
  });

  $http.get(fusio_url + 'backend/dashboard/latest_requests').success(function (data) {
    $scope.latestRequests = data.entry;
  });

  $http.get(fusio_url + 'backend/dashboard/latest_apps').success(function (data) {
    $scope.latestApps = data.entry;
  });

  $http.get(fusio_url + 'backend/statistic/errors_per_route' + query).success(function (data) {
    $scope.errorsPerRoute = data;
  });

}]);
