'use strict';

angular.module('fusioApp.app', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/app', {
		templateUrl: 'app/app/index.html',
		controller: 'AppCtrl'
	});
}])

.controller('AppCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/app?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.apps = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/app?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.apps = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get(fusio_url + 'backend/app?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.apps = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/app/create.html',
			controller: 'AppCreateCtrl'
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openUpdateDialog = function(app){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/app/update.html',
			controller: 'AppUpdateCtrl',
			resolve: {
				app: function(){
					return app;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openDeleteDialog = function(app){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/app/delete.html',
			controller: 'AppDeleteCtrl',
			resolve: {
				app: function(){
					return app;
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

.controller('AppCreateCtrl', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){

	$scope.app = {
		status: 1,
		name: '',
		url: ''
	};

	$scope.states = [{
		key: 1,
		value: 'Active'
	},{
		key: 2,
		value: 'Pending'
	},{
		key: 3,
		value: 'Deactivated'
	}];

	$scope.create = function(app){
		$http.post(fusio_url + 'backend/app', app)
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

.controller('AppUpdateCtrl', ['$scope', '$http', '$modalInstance', 'app', function($scope, $http, $modalInstance, app){

	$scope.app = app;

	$scope.states = [{
		key: 1,
		value: 'Active'
	},{
		key: 2,
		value: 'Pending'
	},{
		key: 3,
		value: 'Deactivated'
	}];

	$http.get(fusio_url + 'backend/scope?count=1024').success(function(data){
		$scope.scopes = data.entry;
	});

	$scope.update = function(app){
		$http.put(fusio_url + 'backend/app/' + app.id, app)
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

	$http.get(fusio_url + 'backend/app/' + app.id)
		.success(function(data){
			$scope.app = data;
		});

}])

.controller('AppDeleteCtrl', ['$scope', '$http', '$modalInstance', 'app', function($scope, $http, $modalInstance, app){

	$scope.app = app;

	$scope.delete = function(app){
		$http.delete(fusio_url + 'backend/app/' + app.id)
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

