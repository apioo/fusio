'use strict';

angular.module('fusioApp.database', ['ngRoute', 'ui.bootstrap'])

.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/database', {
        templateUrl: 'app/database/index.html',
        controller: 'DatabaseCtrl'
    });
}])

.controller('DatabaseCtrl', ['$scope', '$http', '$uibModal', '$routeParams', function($scope, $http, $uibModal){

    $scope.connection = 1;
    $scope.table = null;

    $scope.openCreateDialog = function(){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/create.html',
            controller: 'DatabaseCreateCtrl',
            resolve: {
                connection: function(){
                    return $scope.connection;
                }
            }
        });

        modalInstance.result.then(function(response){
            $scope.response = response;
            $scope.loadTables();
        }, function(){
        });
    };

    $scope.openUpdateDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/update.html',
            controller: 'DatabaseUpdateCtrl',
            resolve: {
                connection: function(){
                    return $scope.connection;
                },
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
            $scope.response = response;
            $scope.loadTables();
            $scope.loadTable($scope.table);
        }, function(){
        });
    };

    $scope.openDeleteDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/delete.html',
            controller: 'DatabaseDeleteCtrl',
            resolve: {
                connection: function(){
                    return $scope.connection;
                },
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
            $scope.response = response;
            $scope.table = null;
            $scope.loadTables();
        }, function(){
        });
    };

    $scope.closeResponse = function(){
        $scope.response = null;
    };

    $scope.loadConnections = function(){
        $http.get(fusio_url + 'backend/connection?count=512').success(function(data){
            $scope.connections = data.entry;
        });
    };

    $scope.loadTable = function(tableName){
        $http.get(fusio_url + 'backend/database/' + $scope.connection + '/' + tableName).success(function(data){
            $scope.table = data;
        });
    };

    $scope.loadTables = function(){
        if (!$scope.connection) {
            return;
        }

        $http.get(fusio_url + 'backend/database/' + $scope.connection).success(function(data){
            $scope.tables = data.entry;

            if ($scope.table) {
                $scope.loadTable($scope.table.name);
            } else if ($scope.tables.length > 0) {
                $scope.loadTable($scope.tables[0].name);
            }
        });
    };

    $scope.loadConnections();
    $scope.loadTables();

}])

.controller('DatabaseCreateCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'connection', function($scope, $http, $uibModalInstance, $uibModal, connection){

    $scope.table = {
        name: '',
        columns: [],
        indexes: [],
        foreignKeys: []
    };

    $scope.types = ['bigint', 'boolean', 'datetime', 'date', 'time', 'decimal', 'integer', 'smallint', 'string', 'text', 'binary', 'blob', 'float', 'guid', 'json', 'object', 'array', 'simple_array'];

    $scope.create = function(table){
        $http.post(fusio_url + 'backend/database/' + connection + '/', table)
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

    $scope.openIndexesDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/indexes.html',
            controller: 'DatabaseIndexesCtrl',
            resolve: {
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.openFksDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/fks.html',
            controller: 'DatabaseFksCtrl',
            resolve: {
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.addColumn = function(){
        if (!$scope.table.columns) {
            $scope.table.columns = [];
        }

        $scope.table.columns.push({
            name: "column" + ($scope.table.columns.length + 1),
            type: "string"
        });
    };

    $scope.removeColumn = function(name){
        var columns = [];
        for (var i = 0; i < $scope.table.columns.length; i++) {
            if ($scope.table.columns[i].name != name) {
                columns.push($scope.table.columns[i]);
            }
        }
        $scope.table.columns = columns;
    };

    $scope.addColumn();

}])

.controller('DatabaseUpdateCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'connection', 'table', function($scope, $http, $uibModalInstance, $uibModal, connection, table){

    var tableCopy = angular.copy(table);

    $scope.table = tableCopy;

    $scope.types = ['bigint', 'boolean', 'datetime', 'date', 'time', 'decimal', 'integer', 'smallint', 'string', 'text', 'binary', 'blob', 'float', 'guid', 'json', 'object', 'array', 'simple_array'];

    $scope.update = function(table){
        $http.put(fusio_url + 'backend/database/' + connection + '/' + table.name + '?preview=1', table)
            .success(function(data){
                if (data.success === true) {
                    // if the preview was successful show the sql and ask for 
                    // confirmation
                    var modalInstance = $uibModal.open({
                        size: 'md',
                        backdrop: 'static',
                        templateUrl: 'app/database/confirm.html',
                        controller: 'DatabaseConfirmCtrl',
                        resolve: {
                            response: function(){
                                return data;
                            },
                            table: function(){
                                return table;
                            }
                        }
                    });

                    modalInstance.result.then(function(table){
                        $http.put(fusio_url + 'backend/database/' + connection + '/' + table.name, table)
                            .success(function(data){
                                $scope.response = data;
                                if (data.success === true) {
                                    $uibModalInstance.close(data);
                                }
                            })
                            .error(function(data){
                                $scope.response = data;
                            });
                    }, function(){
                    });
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

    $scope.openIndexesDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/indexes.html',
            controller: 'DatabaseIndexesCtrl',
            resolve: {
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.openFksDialog = function(table){
        var modalInstance = $uibModal.open({
            size: 'lg',
            backdrop: 'static',
            templateUrl: 'app/database/fks.html',
            controller: 'DatabaseFksCtrl',
            resolve: {
                table: function(){
                    return table;
                }
            }
        });

        modalInstance.result.then(function(response){
        }, function(){
        });
    };

    $scope.addColumn = function(){
        if (!$scope.table.columns) {
            $scope.table.columns = [];
        }

        $scope.table.columns.push({
            name: "column" + ($scope.table.columns.length + 1),
            type: "string"
        });
    };

    $scope.removeColumn = function(name){
        var columns = [];
        for (var i = 0; i < $scope.table.columns.length; i++) {
            if ($scope.table.columns[i].name != name) {
                columns.push($scope.table.columns[i]);
            }
        }
        $scope.table.columns = columns;
    };

}])

.controller('DatabaseDeleteCtrl', ['$scope', '$http', '$uibModalInstance', '$uibModal', 'connection', 'table', function($scope, $http, $uibModalInstance, $uibModal, connection, table){

    $scope.table = table;

    $scope.delete = function(table){
        // preview post
        $http.delete(fusio_url + 'backend/database/' + connection + '/' + table.name + '?preview=1')
            .success(function(data){
                if (data.success === true) {
                    // if the preview was successful show the sql and ask for 
                    // confirmation
                    var modalInstance = $uibModal.open({
                        size: 'md',
                        backdrop: 'static',
                        templateUrl: 'app/database/confirm.html',
                        controller: 'DatabaseConfirmCtrl',
                        resolve: {
                            response: function(){
                                return data;
                            },
                            table: function(){
                                return table;
                            }
                        }
                    });

                    modalInstance.result.then(function(table){
                        $http.delete(fusio_url + 'backend/database/' + connection + '/' + table.name)
                            .success(function(data){
                                $scope.response = data;
                                if (data.success === true) {
                                    $uibModalInstance.close(data);
                                }
                            })
                            .error(function(data){
                                $scope.response = data;
                            });
                    }, function(){
                    });
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

.controller('DatabaseIndexesCtrl', ['$scope', '$uibModalInstance', 'table', function($scope, $uibModalInstance, table){

    $scope.table = table;

    $scope.update = function(table){
        $scope.close();
    };

    $scope.removeIndex = function(name){
        var indexes = [];
        for (var i = 0; i < $scope.table.indexes.length; i++) {
            if ($scope.table.indexes[i].name != name) {
                indexes.push($scope.table.indexes[i]);
            }
        }
        $scope.table.indexes = indexes;
    };

    $scope.addIndex = function(){
        var columnName = $scope.table.columns[0].name;
        $scope.table.indexes.push({
            name: 'index' + ($scope.table.indexes.length + 1),
            columns: [columnName],
            primary: false,
            unique: false
        });
    };

    $scope.close = function(){
        $uibModalInstance.dismiss('cancel');
    };

}])

.controller('DatabaseFksCtrl', ['$scope', '$uibModalInstance', 'table', function($scope, $uibModalInstance, table){

    $scope.table = table;

    $scope.update = function(table){
        $scope.close();
    };

    $scope.removeFk = function(name){
        var fks = [];
        for (var i = 0; i < $scope.table.foreignKeys.length; i++) {
            if ($scope.table.foreignKeys[i].name != name) {
                fks.push($scope.table.foreignKeys[i]);
            }
        }
        $scope.table.foreignKeys = fks;
    };

    $scope.addFk = function(){
        var columnName = $scope.table.columns[0].name;
        $scope.table.foreignKeys.push({
            name: 'FK' + ($scope.table.foreignKeys.length + 1),
            columns: [columnName],
            foreignTable: '',
            foreignColumns: []
        });
    };

    $scope.close = function(){
        $uibModalInstance.dismiss('cancel');
    };

}])

.controller('DatabaseConfirmCtrl', ['$scope', '$uibModalInstance', 'table', 'response', function($scope, $uibModalInstance, table, response){

    $scope.response = response;

    $scope.confirm = function(){
        $uibModalInstance.close(table);
    };

    $scope.close = function(){
        $uibModalInstance.dismiss('cancel');
    };

}]);


