'use strict';

module.exports = function($scope, $http, $uibModal, $timeout, fusio) {

  // set initial date range
  var from = new Date();
  from.setMonth(from.getMonth() - 1);
  var to = new Date();

  $scope.search = '';
  $scope.filter = {
    from: from,
    to: to
  };

  $scope.routes = [];
  $scope.apps = [];

  $scope.load = function() {
    var search = '';
    if ($scope.search) {
      search = encodeURIComponent($scope.search);
    }

    $http.get(fusio.baseUrl + 'backend/log?search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.logs = data.entry;
      });
  };

  $scope.pageChanged = function() {
    var startIndex = ($scope.startIndex - 1) * 16;
    var search = encodeURIComponent($scope.search);

    $http.get(fusio.baseUrl + 'backend/log?startIndex=' + startIndex + '&search=' + search)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.logs = data.entry;
      });
  };

  $scope.doFilter = function() {
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

    $http.get(fusio.baseUrl + 'backend/log?' + query)
      .then(function(response) {
        var data = response.data;
        $scope.totalResults = data.totalResults;
        $scope.startIndex = 0;
        $scope.logs = data.entry;
      });
  };

  $scope.openDetailDialog = function(log) {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/log/detail.html',
      controller: 'LogDetailCtrl',
      resolve: {
        log: function() {
          return log;
        }
      }
    });

    modalInstance.result.then(function(response) {
      $scope.response = response;
      $scope.load();

      $timeout(function() {
        $scope.response = null;
      }, 2000);
    }, function() {
    });
  };

  $scope.openFilterDialog = function() {
    var modalInstance = $uibModal.open({
      size: 'lg',
      backdrop: 'static',
      templateUrl: 'app/controller/statistic/filter.html',
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

  $scope.load();

};
