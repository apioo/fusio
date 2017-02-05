'use strict';

var angular = require('angular');

angular.module('fusioApp.action', ['ngRoute', 'ui.ace'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider.when('/action', {
    templateUrl: 'app/controller/action/index.html',
    controller: 'ActionCtrl'
  });
  $routeProvider.when('/action/designer/:action_id', {
    templateUrl: 'app/controller/action/designer.html',
    controller: 'ActionDesignerCtrl'
  });
}])

.controller('ActionCtrl', require('./action'))
.controller('ActionCreateCtrl', require('./create'))
.controller('ActionUpdateCtrl', require('./update'))
.controller('ActionDeleteCtrl', require('./delete'))
.controller('ActionDesignerCtrl', require('./designer'))

;
