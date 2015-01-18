
angular.service('Session', function(){
	this.create = function (sessionId, userId) {
		this.id = sessionId;
		this.userId = userId;
	};
	this.destroy = function () {
		this.id = null;
		this.userId = null;
	};
	return this;
});
