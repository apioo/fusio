'use strict';

angular.module('fusioApp.log', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/log', {
    templateUrl: 'app/log/index.html',
    controller: 'LogCtrl'
  });
}])

.controller('LogCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

    // set initial date range
    var from = new Date();
    from.setMonth(from.getMonth() - 1);
    var to = new Date();

    $scope.filter = {
        from: from,
        to: to
    };

	$scope.routes = [];
	$scope.apps = [];

	$scope.load = function(){
        var search = '';
        if ($scope.search) {
            search = encodeURIComponent($scope.search);
        }

		$http.get(fusio_url + 'backend/log?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.logs = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/log?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.logs = data.entry;
		});
	};

	$scope.doFilter = function(){
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

		$http.get(fusio_url + 'backend/log?' + query).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.logs = data.entry;
		});
	};

	$scope.openDetailDialog = function(log){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/log/detail.html',
			controller: 'LogDetailCtrl',
			resolve: {
				log: function(){
					return log;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();

			$timeout(function(){
				$scope.response = null;
			}, 2000);
		}, function(){
		});
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

	$scope.load();

}])

.controller('LogDetailCtrl', ['$scope', '$http', '$modal', '$modalInstance', 'log', function($scope, $http, $modal, $modalInstance, log){

	$scope.log = log;

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

	$scope.openTraceDialog = function(error){
		$modal.open({
			size: 'md',
			template: '<div class="modal-body"><pre>' + error.trace + '</pre></div>'
		});
	};

	$http.get(fusio_url + 'backend/log/' + log.id)
		.success(function(data){
			$scope.log = data;
		});

}]);
