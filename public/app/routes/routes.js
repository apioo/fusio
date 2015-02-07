'use strict';

angular.module('fusioApp.routes', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/routes', {
		templateUrl: 'app/routes/index.html',
		controller: 'RoutesCtrl'
	});
}])

.controller('RoutesCtrl', ['$scope', '$http', '$modal', '$timeout', function($scope, $http, $modal, $timeout){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/routes?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.routes = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/routes?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.routes = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/routes?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.routes = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/routes/create.html',
			controller: 'RoutesCreateCtrl'
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

	$scope.openUpdateDialog = function(route){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/routes/update.html',
			controller: 'RoutesUpdateCtrl',
			resolve: {
				route: function(){
					return route;
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

	$scope.openDeleteDialog = function(route){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/routes/delete.html',
			controller: 'RoutesDeleteCtrl',
			resolve: {
				route: function(){
					return route;
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

	$scope.load();

}])

.controller('RoutesCreateCtrl', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){

	$scope.route = {
		methods: "",
		path: ""
	};
	$scope.controllers = [{
		id: 'controller-schema-abstract',
		name: 'Schema API'
	},{
		id: 'controller-view-abstract',
		name: 'View API'
	}];
	$scope.config = 'controller-schema-abstract';

	// schema api config
	$scope.options = [];

	$scope.methods = ['GET', 'POST', 'PUT', 'DELETE'];
	$scope.requestSchema = [{
		id: 0,
		name: 'Disabled'
	},{
		id: 1,
		name: 'Passthru'
	},{
		id: 2,
		name: 'foo-schema'
	}];
	$scope.responseSchema = [{
		id: 0,
		name: 'Disabled'
	},{
		id: 1,
		name: 'Passthru'
	},{
		id: 2,
		name: 'bar-schema'
	}];
	$scope.actions = [{
		id: 1,
		name: 'sql-action'
	}];

	$scope.create = function(route){

		var controller = null;
		if ($scope.config == 'controller-schema-abstract') {
			controller = 'Fusio\Controller\SchemaApiAbstract';
		} else if ($scope.config == 'controller-view-abstract') {
			controller = 'Fusio\Controller\ViewApiAbstract';
		}

		var data = {
			methods: route.methods,
			path: route.path,
			controller: controller,
			options: $scope.options
		};

		$http.post('http://127.0.0.1/projects/fusio/public/index.php/backend/routes', route)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$modalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

	$scope.addOptionRow = function(){
		$scope.options.push({
			method: 'GET',
			request: 1,
			response: 1,
			action: 1
		});
	};

	$scope.removeOptionRow = function(row){
		var newOptions = [];
		for (var i = 0; i < $scope.options.length; i++) {
			var option = $scope.options[i];
			if (option['$$hashKey'] != row['$$hashKey']) {
				newOptions.push($scope.options[i]);
			}
		}
		$scope.options = newOptions;
	};

	$scope.addOptionRow();

}])

.controller('RoutesUpdateCtrl', ['$scope', '$http', '$modalInstance', 'route', function($scope, $http, $modalInstance, route){

	$scope.route = route;
	$scope.controllers = [];

	$scope.update = function(route){
		$http.put('http://127.0.0.1/projects/fusio/public/index.php/backend/routes/' + route.id, route)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$modalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/controller')
		.success(function(data){
			$scope.controllers = data.entry;
		});

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

}])

.controller('RoutesDeleteCtrl', ['$scope', '$http', '$modalInstance', 'route', function($scope, $http, $modalInstance, route){

	$scope.route = route;

	$scope.delete = function(route){
		$http.delete('http://127.0.0.1/projects/fusio/public/index.php/backend/routes/' + route.id)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$modalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

}]);

