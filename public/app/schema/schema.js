'use strict';

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/schema', {
		templateUrl: 'app/schema/index.html',
		controller: 'SchemaCtrl'
	});
}])

.controller('SchemaCtrl', ['$scope', '$http', '$modal', '$routeParams', '$location', function($scope, $http, $modal, $routeParams, $location){

	$scope.response = null;
	$scope.search = '';
	$scope.routes;
	$scope.routeId = parseInt($routeParams.routeId);

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);
		var routeId = $scope.routeId;

		$http.get(fusio_url + 'backend/schema?search=' + search + '&routeId=' + routeId).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.startIndex = 0;
			$scope.schemas = data.entry;
		});
	};

	$scope.loadRoutes = function(){
		$http.get(fusio_url + 'backend/routes').success(function(data){
			$scope.routes = data.entry;
		});
	};

	$scope.changeRoute = function(){
		$location.search('routeId', $scope.routeId);
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/schema?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.schemas = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		var routeId = $scope.routeId;

		$http.get(fusio_url + 'backend/schema?search=' + search + '&routeId=' + routeId).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.startIndex = 0;
			$scope.schemas = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			backdrop: 'static',
			templateUrl: 'app/schema/create.html',
			controller: 'SchemaCreateCtrl'
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openUpdateDialog = function(schema){
		var modalInstance = $modal.open({
			size: 'lg',
			backdrop: 'static',
			templateUrl: 'app/schema/update.html',
			controller: 'SchemaUpdateCtrl',
			resolve: {
				schema: function(){
					return schema;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openDeleteDialog = function(schema){
		var modalInstance = $modal.open({
			size: 'lg',
			backdrop: 'static',
			templateUrl: 'app/schema/delete.html',
			controller: 'SchemaDeleteCtrl',
			resolve: {
				schema: function(){
					return schema;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

	$scope.load();
	$scope.loadRoutes();

}])

.controller('SchemaCreateCtrl', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){

	$scope.schema = {
		name: '',
		source: ''
	};

	$scope.create = function(schema){
		$http.post(fusio_url + 'backend/schema', schema)
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

	$scope.closeResponse = function(){
		$scope.response = null;
	};

}])

.controller('SchemaUpdateCtrl', ['$scope', '$http', '$modalInstance', '$modal', 'schema', function($scope, $http, $modalInstance, $modal, schema){

	$scope.schema = schema;

	$scope.update = function(schema){
		$http.put(fusio_url + 'backend/schema/' + schema.id, schema)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$scope.loadPreview(schema.id);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

	$scope.loadPreview = function(schemaId){
		$http.get(fusio_url + 'backend/schema/preview/' + schemaId)
			.success(function(data){
				$scope.preview = data;
			});
	};

	$http.get(fusio_url + 'backend/schema/' + schema.id)
		.success(function(data){
			$scope.schema = data;
		});

}])

.controller('SchemaDeleteCtrl', ['$scope', '$http', '$modalInstance', 'schema', function($scope, $http, $modalInstance, schema){

	$scope.schema = schema;

	$scope.delete = function(schema){
		$http.delete(fusio_url + 'backend/schema/' + schema.id)
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

	$scope.closeResponse = function(){
		$scope.response = null;
	};

}]);

