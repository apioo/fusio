'use strict';

angular.module('fusioApp.schema', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/schema', {
        templateUrl: 'app/schema/index.html',
        controller: 'SchemaCtrl'
    });
}])

.controller('SchemaCtrl', ['$scope', '$http', '$uibModal', '$routeParams', '$location', function($scope, $http, $uibModal, $routeParams, $location){

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
        var modalInstance = $uibModal.open({
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
        var modalInstance = $uibModal.open({
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
        var modalInstance = $uibModal.open({
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

.controller('SchemaCreateCtrl', ['$scope', '$http', '$uibModalInstance', function($scope, $http, $uibModalInstance){

    $scope.schema = {
        name: '',
        source: ''
    };

    $scope.create = function(schema){
        if (typeof schema.source == 'string') {
            schema.source = JSON.parse(schema.source);
        }

        $http.post(fusio_url + 'backend/schema', schema)
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

}])

.controller('SchemaUpdateCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'schema', function($scope, $http, $uibModalInstance, $uibModal, schema){

    $scope.schema = schema;

    $scope.update = function(schema){
        if (typeof schema.source == 'string') {
            schema.source = JSON.parse(schema.source);
        }

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
        $uibModalInstance.dismiss('cancel');
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
            if (typeof data.source != 'string') {
                data.source = JSON.stringify(data.source, null, 4);
            }

            $scope.schema = data;
        });

}])

.controller('SchemaDeleteCtrl', ['$scope', '$http', '$uibModalInstance', 'schema', function($scope, $http, $uibModalInstance, schema){

    $scope.schema = schema;

    $scope.delete = function(schema){
        $http.delete(fusio_url + 'backend/schema/' + schema.id)
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

