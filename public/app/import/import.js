'use strict';

angular.module('fusioApp.import', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/import', {
        templateUrl: 'app/import/index.html',
        controller: 'ImportCtrl'
    });
}])

.controller('ImportCtrl', ['$scope', '$http', '$modal', function($scope, $http, $modal){

    $scope.source;
    $scope.error;
    $scope.success = false;

    $scope.transform = function(source){
        $http.post(fusio_url + 'backend/import/raml', {schema: source}).success(function(data){
            if ('success' in data && data.success === false) {
                $scope.error = data.message;
                return;
            } else {
                $scope.error = null;
            }

            $scope.openPreviewDialog(data);
        }).error(function(data){
            if ('success' in data && data.success === false) {
                $scope.error = data.message;
            } else {
                $scope.error = 'An unknown error occured';
            }
        });
    };

    $scope.openPreviewDialog = function(data){
        var modalInstance = $modal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/import/preview.html',
            controller: 'ImportPreviewCtrl',
            resolve: {
                data: function(){
                    return data;
                }
            }
        });

        modalInstance.result.then(function(response){
            $scope.success = true;
        }, function(){
        });
    };

}])

.controller('ImportPreviewCtrl', ['$scope', '$http', '$modalInstance', '$modal', 'data', function($scope, $http, $modalInstance, $modal, data){

    $scope.data = data;

    $scope.doProcess = function(){
        $http.post(fusio_url + 'backend/import/process', data).success(function(data){
            if ('success' in data && data.success === false) {
                $scope.error = data.message;
                return;
            } else {
                $scope.error = null;
            }

            $modalInstance.close();
        }).error(function(data){
            if ('success' in data && data.success === false) {
                $scope.error = data.message;
            } else {
                $scope.error = 'An unknown error occured';
            }
        });
    };

    $scope.openRouteDialog = function(route){
        var modalInstance = $modal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/import/route.html',
            controller: 'ImportRouteCtrl',
            resolve: {
                route: function(){
                    return route;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.openSchemaDialog = function(schema){
        console.log(schema);
        var modalInstance = $modal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/import/schema.html',
            controller: 'ImportSchemaCtrl',
            resolve: {
                schema: function(){
                    return schema;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.close = function(){
        $modalInstance.dismiss('cancel');
    };

}])

.controller('ImportRouteCtrl', ['$scope', '$http', '$modalInstance', 'route', function($scope, $http, $modalInstance, route){

    $scope.route = route;

    $scope.statuuus = [{
        key: 4,
        value: "Development"
    },{
        key: 1,
        value: "Production"
    },{
        key: 2,
        value: "Deprecated"
    },{
        key: 3,
        value: "Closed"
    }];

    $scope.close = function(){
        $modalInstance.dismiss('cancel');
    };

}])

.controller('ImportSchemaCtrl', ['$scope', '$http', '$modalInstance', 'schema', function($scope, $http, $modalInstance, schema){

    $scope.schema = schema;

    $scope.close = function(){
        $modalInstance.dismiss('cancel');
    };

}]);

