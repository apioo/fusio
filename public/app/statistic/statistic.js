'use strict';

angular.module('fusioApp.statistic', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function ($routeProvider) {
  $routeProvider.when('/statistic', {
    templateUrl: 'app/statistic/index.html',
    controller: 'StatisticCtrl'
  });
}])

.controller('StatisticCtrl', ['$scope', '$http', '$uibModal', '$compile', function ($scope, $http, $uibModal, $compile) {

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

  $scope.doFilter = function () {
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

    $http.get(fusio_url + 'backend/statistic/' + statistic + '?' + query).success(function (data) {
      $scope.chart = data;
    });
  };

  $scope.openFilterDialog = function () {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/statistic/filter.html',
      controller: 'StatisticFilterCtrl',
      resolve: {
        filter: function () {
          return $scope.filter;
        }
      }
    });

    modalInstance.result.then(function (filter) {
      $scope.filter = filter;
      $scope.doFilter();
    }, function () {
    });
  };

  $scope.doFilter();

}])


.controller('StatisticFilterCtrl', ['$scope', '$http', '$uibModalInstance', 'filter', function ($scope, $http, $uibModalInstance, filter) {

  $scope.filter = filter;

  $scope.doFilter = function () {
    $uibModalInstance.close($scope.filter);
  };

  $scope.close = function () {
    $uibModalInstance.dismiss('cancel');
  };

  $http.get(fusio_url + 'backend/routes')
    .success(function (data) {
      $scope.routes = data.entry;
    });

  $http.get(fusio_url + 'backend/app')
    .success(function (data) {
      $scope.apps = data.entry;
    });

}]);
