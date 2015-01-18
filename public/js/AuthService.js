
angular.factory('AuthService', function($http, Session){
	var authService = {};
 
	authService.login = function(credentials){
		return $http
			.post('/login', credentials)
			.then(function(res){
				Session.create(res.data.id, res.data.user.id);
				return res.data.user;
			});
	};
 
	authService.isAuthenticated = function(){
		return !!Session.userId;
	};

	return authService;
})

