'use strict';

module.exports = function($scope, $http, $uibModalInstance, fusio) {

  $scope.rate = {
    priority: 0,
    name: '',
    rateLimit: 1800,
    timespan: '',
    allocation: [{
      routeId: null,
      appId: null,
      authenticated: null,
      parameters: null
    }]
  };

  $scope.timespan = {
    value: 1,
    unit: 'hour'
  };

  $scope.intervals = [{
    key: 'minute',
    value: 'minute'
  }, {
    key: 'hour',
    value: 'hour'
  }, {
    key: 'day',
    value: 'day'
  }, {
    key: 'week',
    value: 'week'
  }, {
    key: 'month',
    value: 'month'
  }];

  $scope.status = [{
    key: null,
    value: 'Yes/No'
  }, {
    key: true,
    value: 'Yes'
  }, {
    key: false,
    value: 'No'
  }];

  $scope.routes = [];
  $scope.apps = [];

  $scope.create = function(rate) {
    var data = angular.copy(rate);
    data.timespan = $scope.getTimespan($scope.timespan);
    data.allocation = $scope.removeNullValuesFromAllocation(rate.allocation);

    $http.post(fusio.baseUrl + 'backend/rate', data)
      .then(function(response) {
        var data = response.data;
        $scope.response = data;
        if (data.success === true) {
          $uibModalInstance.close(data);
        }
      })
      .catch(function(response) {
        $scope.response = response.data;
      });
  };

  $scope.close = function() {
    $uibModalInstance.dismiss('cancel');
  };

  $scope.closeResponse = function() {
    $scope.response = null;
  };

  $scope.getRoutes = function() {
    $http.get(fusio.baseUrl + 'backend/routes')
      .then(function(response) {
        var data = response.data;
        if (angular.isArray(data.entry)) {
          var routes = data.entry;
          routes.unshift({
            id: null,
            path: 'Every route'
          });
          $scope.routes = routes;
        }
      });
  };

  $scope.getApps = function() {
    $http.get(fusio.baseUrl + 'backend/app')
      .then(function(response) {
        var data = response.data;
        if (angular.isArray(data.entry)) {
          var apps = data.entry;
          apps.unshift({
            id: null,
            name: 'Every app'
          });
          $scope.apps = apps;
        }
      });
  };

  $scope.addAllocation = function() {
    $scope.rate.allocation.push({
      routeId: null,
      appId: null,
      authenticated: true,
      parameters: null
    });
  };

  $scope.removeAllocation = function(index) {
    var allocation = $scope.rate.allocation;
    allocation.splice(index, 1);
    $scope.rate.allocation = allocation;
  };

  $scope.removeNullValuesFromAllocation = function(allocation) {
    var data = [];
    for (var i = 0; i < allocation.length; i++) {
      data.push($scope.removeNullValuesFromObject(allocation[i]));
    }
    return data;
  };

  $scope.removeNullValuesFromObject = function(object) {
    var row = {};
    for (var key in object) {
      if (object.hasOwnProperty(key) && object[key] !== null) {
        row[key] = object[key];
      }
    }
    return row;
  };

  $scope.getTimespan = function(timespan) {
    if (timespan.unit == 'minute') {
      return 'PT' + timespan.value + 'M';
    } else if (timespan.unit == 'hour') {
      return 'PT' + timespan.value + 'H';
    } else if (timespan.unit == 'day') {
      return 'P' + timespan.value + 'D';
    } else if (timespan.unit == 'week') {
      return 'P' + timespan.value + 'W';
    } else if (timespan.unit == 'month') {
      return 'P' + timespan.value + 'M';
    }
  };

  $scope.getRoutes();
  //$scope.getApps();

};
