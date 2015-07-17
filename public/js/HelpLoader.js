
fusioApp.factory('helpLoader', ['$http', '$showdown', '$q', '$modal', function($http, $showdown, $q, $modal){
    var helper = {};
 
    helper.load = function(path){
        return $q(function(resolve, reject){
            $http.get(path).success(function(data){
                resolve($showdown.makeHtml(data));
            }).error(function(data){
                reject(data);
            });
        });
    };

    helper.showDialog = function(path){
        this.load(path).then(function(html){
            $modal.open({
                size: 'md',
                template: '<div class="modal-body">' + html + '</div>'
            });
        });
    };

    return helper;
}]);

