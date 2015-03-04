'use strict';

angular.module('fusioApp.user', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
	$routeProvider.when('/user', {
		templateUrl: 'app/user/index.html',
		controller: 'UserCtrl'
	});
}])

.controller('UserCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

	$scope.response = null;
	$scope.search = '';

	$scope.load = function(){
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/user?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.users = data.entry;
		});
	};

	$scope.pageChanged = function(){
		var startIndex = ($scope.startIndex - 1) * 16;
		var search = encodeURIComponent($scope.search);

		$http.get(fusio_url + 'backend/user?startIndex=' + startIndex + '&search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.users = data.entry;
		});
	};

	$scope.doSearch = function(search){
		var search = encodeURIComponent(search);
		$http.get(fusio_url + 'backend/user?search=' + search).success(function(data){
			$scope.totalItems = data.totalItems;
			$scope.startIndex = 0;
			$scope.users = data.entry;
		});
	};

	$scope.openCreateDialog = function(){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/user/create.html',
			controller: 'UserCreateCtrl'
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openUpdateDialog = function(user){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/user/update.html',
			controller: 'UserUpdateCtrl',
			resolve: {
				user: function(){
					return user;
				}
			}
		});

		modalInstance.result.then(function(response){
			$scope.response = response;
			$scope.load();
		}, function(){
		});
	};

	$scope.openDeleteDialog = function(user){
		var modalInstance = $modal.open({
			size: 'lg',
			templateUrl: 'app/user/delete.html',
			controller: 'UserDeleteCtrl',
			resolve: {
				user: function(){
					return user;
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

.controller('UserCreateCtrl', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){

	$scope.user = {
		status: 0,
		name: '',
		password: ''
	};

	$scope.statuuus = [{
		id: 0,
		name: 'Consumer'
	},{
		id: 1,
		name: 'Administrator'
	},{
		id: 2,
		name: 'Disabled'
	}];

	$scope.create = function(user){
		$http.post(fusio_url + 'backend/user', user)
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

.controller('UserUpdateCtrl', ['$scope', '$http', '$modalInstance', 'user', function($scope, $http, $modalInstance, user){

	$scope.user = user;

	$scope.statuuus = [{
		id: 0,
		name: 'Consumer'
	},{
		id: 1,
		name: 'Administrator'
	},{
		id: 2,
		name: 'Disabled'
	}];

	$scope.update = function(user){
		$http.put(fusio_url + 'backend/user/' + user.id, user)
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

.controller('UserDeleteCtrl', ['$scope', '$http', '$modalInstance', 'user', function($scope, $http, $modalInstance, user){

	$scope.user = user;

	$scope.delete = function(user){
		$http.delete(fusio_url + 'backend/user/' + user.id)
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

