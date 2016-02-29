'use strict';

angular.module('fusioApp.app', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/app', {
		templateUrl: 'app/app/index.html',
		controller: 'AppCtrl'
	});
}])

.controller('AppCtrl', ['$scope', '$http', '$uibModal', function($scope, $http, $uibModal){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/app?search=' + search).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.startIndex = 0;
			$scope.apps = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/app?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.apps = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get(fusio_url + 'backend/app?search=' + search).success(function(data){
			$scope.totalResults = data.totalResults;
			$scope.startIndex = 0;
			$scope.apps = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $uibModal.open({
			size: 'lg',
			backdrop: 'static',
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
		var modalInstance = $uibModal.open({
			size: 'lg',
			backdrop: 'static',
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
		var modalInstance = $uibModal.open({
			size: 'lg',
			backdrop: 'static',
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

.controller('AppCreateCtrl', ['$scope', '$http', '$uibModalInstance', function($scope, $http, $uibModalInstance){

	$scope.app = {
		status: 1,
		name: '',
		url: '',
		scopes: []
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
					$uibModalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$uibModalInstance.dismiss('cancel');
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

	$scope.getUsers = function(name){
		return $http.get(fusio_url + 'backend/user?search=' + encodeURIComponent(name)).then(function(response){
			if (angular.isArray(response.data.entry)) {
				return response.data.entry;
				return response.data.entry.map(function(item){
					return item.name;
				});
			} else {
				return [];
			}
		});
	};

	$http.get(fusio_url + 'backend/scope?count=1024').success(function(data){
		$scope.scopes = data.entry;
	});

}])

.controller('AppUpdateCtrl', ['$scope', '$http', '$uibModalInstance', 'app', function($scope, $http, $uibModalInstance, app){

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

		$scope.loadApp();
	});

	$scope.update = function(app){
		if (app.tokens) {
			delete app.tokens;
		}

		$http.put(fusio_url + 'backend/app/' + app.id, app)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$uibModalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$uibModalInstance.dismiss('cancel');
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

	$scope.loadApp = function(){
		$http.get(fusio_url + 'backend/app/' + app.id)
			.success(function(data){
				var scopes = [];
				if (angular.isArray(data.scopes)) {
					for (var i = 0; i < $scope.scopes.length; i++) {
						var found = null;
						for (var j = 0; j < data.scopes.length; j++) {
							if ($scope.scopes[i].name == data.scopes[j]) {
								found = $scope.scopes[i].name;
								break;
							}
						}
						scopes.push(found);
					}
				}
				data.scopes = scopes;

				$scope.app = data;
			});
	};

	$scope.removeToken = function(token){
		$http.delete(fusio_url + 'backend/app/' + app.id + '/token/' + token.id)
			.success(function(data){
				if ($scope.app.tokens) {
					var tokens = [];
					for (var i = 0; i < $scope.app.tokens.length; i++) {
						if ($scope.app.tokens[i].id != token.id) {
							tokens.push($scope.app.tokens[i]);
							break;
						}
					}
					$scope.app.tokens = tokens;
				}
			});
	};

}])

.controller('AppDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'app', function($scope, $http, $uibModalInstance, app){

	$scope.app = app;

	$scope.delete = function(app){
		$http.delete(fusio_url + 'backend/app/' + app.id)
			.success(function(data){
				$scope.response = data;
				if (data.success === true) {
					$uibModalInstance.close(data);
				}
			})
			.error(function(data){
				$scope.response = data;
			});
	};

	$scope.close = function(){
		$uibModalInstance.dismiss('cancel');
	};

	$scope.closeResponse = function(){
		$scope.response = null;
	};

}]);

