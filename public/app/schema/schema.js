'use strict';

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/schema', {
		templateUrl: 'app/schema/index.html',
		controller: 'SchemaCtrl'
	});
}])

.controller('SchemaCtrl', ['$scope', '$http', '$modal', '$timeout', function($scope, $http, $modal, $timeout){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/schema?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.schemas = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/schema?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.schemas = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/schema?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.schemas = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/schema/create.html',
			controller: 'SchemaCreateCtrl'
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

	$scope.openUpdateDialog = function(schema){
		var modalInstance = $modal.open({
			size: 'lg',
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

			$timeout(function(){
				$scope.response = null;
			}, 2000);
		}, function(){
		});
	};

	$scope.openDeleteDialog = function(schema){
		var modalInstance = $modal.open({
			size: 'lg',
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

			$timeout(function(){
				$scope.response = null;
			}, 2000);
		}, function(){
		});
	};

	$scope.load();

}])

.controller('SchemaCreateCtrl', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){

	$scope.schema = {
		name: '',
		extends_id: '',
		property_name: '',
		extend: '',
		fields: []
	};
	$scope.fieldTypes = ['Array', 'Boolean', 'Date', 'Datetime', 'Float', 'Integer', 'Object', 'String', 'Time']

	$scope.create = function(schema){

		var data = {};

		$http.post('http://127.0.0.1/projects/fusio/public/index.php/backend/schema', schema)
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

	$scope.addFieldRow = function(){
		$scope.schema.fields.push({
			name: '',
			type: 'String'
		});
	};

	$scope.removeFieldRow = function(row){
		var newFields = [];
		for (var i = 0; i < $scope.schema.fields.length; i++) {
			var field = $scope.schema.fields[i];
			if (field['$$hashKey'] != row['$$hashKey']) {
				newFields.push($scope.schema.fields[i]);
			}
		}
		$scope.schema.fields = newFields;
	};

	$scope.addFieldRow();

}])

.controller('SchemaUpdateCtrl', ['$scope', '$http', '$modalInstance', 'schema', function($scope, $http, $modalInstance, schema){

	$scope.schema = schema;
	$scope.schemas = [];

	$scope.update = function(schema){
		$http.put('http://127.0.0.1/projects/fusio/public/index.php/backend/schema/' + schema.id, schema)
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

	$http.get('http://127.0.0.1/projects/fusio/public/index.php/backend/schema')
		.success(function(data){
			$scope.schemas = data.entry;
		});

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

}])

.controller('SchemaDeleteCtrl', ['$scope', '$http', '$modalInstance', 'schema', function($scope, $http, $modalInstance, schema){

	$scope.schema = schema;

	$scope.delete = function(schema){
		$http.delete('http://127.0.0.1/projects/fusio/public/index.php/backend/schema/' + schema.id)
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

