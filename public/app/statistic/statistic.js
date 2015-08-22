'use strict';

angular.module('fusioApp.statistic', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/statistic', {
    templateUrl: 'app/statistic/index.html',
    controller: 'StatisticCtrl'
  });
}])

.controller('StatisticCtrl', ['$scope', '$http', '$modal', '$compile', function($scope, $http, $modal, $compile){

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
    },{
        name: 'Most used routes',
        value: 'most_used_routes'
    },{
        name: 'Most used apps',
        value: 'most_used_apps'
    },{
        name: 'Errors per route',
        value: 'errors_per_route'
    }];

    $scope.doFilter = function(){
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

                query+= key + '=' + encodeURIComponent(value) + '&';
            }
        }

        $http.get(fusio_url + 'backend/statistic/' + statistic + '?' + query).success(function(data){
            $scope.chart = data;
        });
    };

    $scope.doChangeStatistic = function(){
        /*
        angular.element(document.querySelector('#statisticCanvas')).remove();
        var statisticCanvas = '<canvas id="statisticCanvas" class="chart chart-line" height="600" options="{\'responsive\':true,\'maintainAspectRatio\':false}" data="chart.data" labels="chart.labels" series="chart.series"></canvas>';
        var t = $compile(statisticCanvas)($scope);
console.log(t);
        angular.element(document.querySelector('#statisticCanvasContainer')).append(t);
        */

        $scope.doFilter();
    };

    $scope.openFilterDialog = function(){
        var modalInstance = $modal.open({
            size: 'lg',
            templateUrl: 'app/statistic/filter.html',
            controller: 'StatisticFilterCtrl',
            resolve: {
                filter: function(){
                    return $scope.filter;
                }
            }
        });

        modalInstance.result.then(function(filter){
            $scope.filter = filter;
            $scope.doFilter();
        }, function(){
        });
    };

    $scope.doFilter();

}])


.controller('StatisticFilterCtrl', ['$scope', '$http', '$modalInstance', 'filter', function($scope, $http, $modalInstance, filter){

    $scope.filter = filter;

    $scope.doFilter = function(){
        $modalInstance.close($scope.filter);
    };

    $scope.close = function(){
        $modalInstance.dismiss('cancel');
    };

    $http.get(fusio_url + 'backend/routes')
        .success(function(data){
            $scope.routes = data.entry;
        });

    $http.get(fusio_url + 'backend/app')
        .success(function(data){
            $scope.apps = data.entry;
        });

}]);
