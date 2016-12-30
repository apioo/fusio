'use strict';

angular.module('fusioApp.statistic', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/statistic', {
    templateUrl: 'app/statistic/index.html',
    controller: 'StatisticCtrl'
  });
}])

.controller('StatisticCtrl', ['$scope', '$http', '$uibModal', '$compile', 'fusio', function($scope, $http, $uibModal, $compile, fusio) {

  // set initial date range
  var from = new Date();
  from.setMonth(from.getMonth() - 1);
  var to = new Date();

  $scope.filter = {
    from: from,
    to: to
  };
  $scope.chart = {};
  $scope.statistic = 'incoming_requests';

  $scope.statistics = [{
    name: 'Incoming requests',
    value: 'incoming_requests'
  }, {
    name: 'Most used routes',
    value: 'most_used_routes'
  }, {
    name: 'Most used apps',
    value: 'most_used_apps'
  }, {
    name: 'Errors per route',
    value: 'errors_per_route'
  }];

  $scope.doFilter = function() {
    var statistic = $scope.statistic ? $scope.statistic : 'incoming_requests';
    var query = '';
    for (var key in $scope.filter) {
      if ($scope.filter[key]) {
        var value;
        if ($scope.filter[key] instanceof Date) {
          value = $scope.filter[key].toISOString();
        } else {
          value = $scope.filter[key];
        }

        query += key + '=' + encodeURIComponent(value) + '&';
      }
    }

    $http.get(fusio.baseUrl + 'backend/statistic/' + statistic + '?' + query)
      .then(function(response) {
        $scope.chart = response.data;
      });
  };

  $scope.getStatisticName = function(statistic) {
    for (var i = 0; i < $scope.statistics.length; i++) {
      if ($scope.statistics[i].value == statistic) {
        return $scope.statistics[i].name;
      }
    }
    return null;
  };

  $scope.openFilterDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/statistic/filter.html',
      controller: 'StatisticFilterCtrl',
      resolve: {
        filter: function() {
          return $scope.filter;
        }
      }
    });

    modalInstance.result.then(function(filter) {
      $scope.filter = filter;
      $scope.doFilter();
    }, function() {
    });
  };

  $scope.doFilter();

}])

.controller('StatisticFilterCtrl', ['$scope', '$http', '$uibModalInstance', 'fusio', 'filter', function($scope, $http, $uibModalInstance, fusio, filter) {

  $scope.filter = filter;

  $scope.doFilter = function() {
    $uibModalInstance.close($scope.filter);
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $http.get(fusio.baseUrl + 'backend/routes')
    .then(function(response) {
      $scope.routes = response.data.entry;
    });

  $http.get(fusio.baseUrl + 'backend/app')
    .then(function(response) {
      $scope.apps = response.data.entry;
    });

}]);
