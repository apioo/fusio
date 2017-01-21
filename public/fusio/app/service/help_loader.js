'use strict';

module.exports = function($http, $showdown, $q, $uibModal) {
  var helper = {};

  helper.load = function(path) {
    return $q(function(resolve, reject) {
      $http.get(path).success(function(data, status) {
        // if the path has an fragment extract the part of the content
        // with the heading
        var parser = document.createElement('a');
        parser.href = path;

        if (parser.hash) {
          var heading = parser.hash.substr(1);
          if (heading !== '') {
            var regexp = new RegExp('(^###\\s' + heading + '$\\s+([\\s\\S]*?))^###\\s', 'gmi');
            var matches = regexp.exec(data);

            data = matches && matches.length > 0 ? matches[1] : 'Could not found chapter';
          }
        }

        var html = $showdown.makeHtml(data);

        // replace {{ ... }} so that angular does not try to
        // interpret these values
        html = html.replace(/{{/g, '{<!-- -->{');
        html = html.replace(/}}/g, '}<!-- -->}');

        resolve(html);
      }).error(function(data) {
        reject('Could not find help file');
      });
    });
  };

  helper.showDialog = function(path) {
    this.load(path).then(function(html) {
      $uibModal.open({
        size: 'md',
        template: '<div class="modal-body">' + html + '</div>'
      });
    }, function(html) {
      $uibModal.open({
        size: 'md',
        template: '<div class="modal-body"><div class="alert alert-info">' + html + '</div></div>'
      });
    });
  };

  return helper;
};

