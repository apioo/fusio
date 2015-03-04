'use strict';

angular.module('fusioApp.connection', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/connection', {
		templateUrl: 'app/connection/index.html',
		controller: 'ConnectionCtrl'
	});
}])

.controller('ConnectionCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/connection?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.connections = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/connection?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.connections = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get(fusio_url + 'backend/connection?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.connections = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/connection/create.html',
			controller: 'ConnectionCreateCtrl'
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openUpdateDialog = function(connection){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/connection/update.html',
			controller: 'ConnectionUpdateCtrl',
			resolve: {
				connection: function(){
					return connection;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openDeleteDialog = function(connection){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/connection/delete.html',
			controller: 'ConnectionDeleteCtrl',
			resolve: {
				connection: function(){
					return connection;
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

.controller('ConnectionCreateCtrl', ['$scope', '$http', '$modalInstance', 'formBuilder', function($scope, $http, $modalInstance, formBuilder){

	$scope.connection = {
		name: '',
		class: '',
		config: {}
	};

	$scope.connections = [];

	$scope.create = function(connection){
		$http.post(fusio_url + 'backend/connection', connection)
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

	$http.get(fusio_url + 'backend/connection/list')
		.success(function(data){
			$scope.connections = data.connections;

			if (data.connections[0]) {
				$scope.connection.class = data.connections[0].class;
				$scope.loadConfig();
			}
		});

	$scope.close = function(){
		$modalInstance.dismiss('cancel');
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

	$scope.loadConfig = function(){
		if ($scope.connection.class) {
			$http.get(fusio_url + 'backend/connection/form?class=' + encodeURIComponent($scope.connection.class))
				.success(function(data){
					var containerEl = angular.element(document.querySelector('#config-form'));
					containerEl.children().remove();

					var linkFn = formBuilder.buildHtml(data.element, 'connection.config');
					if (angular.isFunction(linkFn)) {
						var el = linkFn($scope);
						containerEl.append(el);
					}
				});
		}
	};

}])

.controller('ConnectionUpdateCtrl', ['$scope', '$http', '$modalInstance', 'formBuilder', 'connection', function($scope, $http, $modalInstance, formBuilder, connection){

	if (angular.isArray(connection.config)) {
		connection.config = {};
	}

	$scope.connection = connection;
	$scope.connections = [];

	$scope.update = function(connection){
		$http.put(fusio_url + 'backend/connection/' + connection.id, connection)
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

	$scope.loadConfig = function(){
		if ($scope.connection.class) {
			$http.get(fusio_url + 'backend/connection/form?class=' + encodeURIComponent($scope.connection.class))
				.success(function(data){
					var containerEl = angular.element(document.querySelector('#config-form'));
					containerEl.children().remove();

					var linkFn = formBuilder.buildHtml(data.element, 'connection.config');
					if (angular.isFunction(linkFn)) {
						var el = linkFn($scope);
						containerEl.append(el);
					}
				});
		}
	};

	$http.get(fusio_url + 'backend/connection/' + connection.id)
		.success(function(data){
			$scope.connection = data;

			$scope.loadConfig();
		});

}])

.controller('ConnectionDeleteCtrl', ['$scope', '$http', '$modalInstance', 'connection', function($scope, $http, $modalInstance, connection){

	$scope.connection = connection;

	$scope.delete = function(connection){
		$http.delete(fusio_url + 'backend/connection/' + connection.id)
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

