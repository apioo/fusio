'use strict';

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/schema', {
		templateUrl: 'app/schema/index.html',
		controller: 'SchemaCtrl'
	});
}])

.controller('SchemaCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/schema?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.schemas = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/schema?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.schemas = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get(fusio_url + 'backend/schema?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
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

	$scope.showValidators = function(schema){
		var modalInstance = $modal.open({
			size: 'lg',
			backdrop: 'static',
			templateUrl: 'app/schema/validator.html',
			controller: 'SchemaValidatorCtrl',
			resolve: {
				schema: function(){
					return schema;
				}
			}
		});

		modalInstance.result.then(function(validators){
			$scope.schema.validators = validators;

			$scope.update(schema);
		}, function(){
		});

	};

	$http.get(fusio_url + 'backend/schema/' + schema.id)
		.success(function(data){
			$scope.schema = data;
		});

}])

.controller('SchemaValidatorCtrl', ['$scope', '$http', '$modalInstance', 'schema', function($scope, $http, $modalInstance, schema){

	$scope.schema = schema;
	$scope.validator = {};

	if (!$scope.schema.validators || !angular.isArray($scope.schema.validators)) {
		$scope.schema.validators = [];
	}

	$scope.create = function(validator){
		$scope.schema.validators.push(validator);
		$scope.validator = {};
	};

	$scope.remove = function(index){
		$scope.schema.validators.splice(index, 1);
	};

	$scope.save = function(){
		$modalInstance.close($scope.schema.validators);
	};

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

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

