module.exports = function(grunt){

  grunt.initConfig({
    concat: {
      options: {
        separator: ';',
      },
      backend: {
        src: [
          './bower_components/jquery/dist/jquery.min.js',
          './bower_components/angular/angular.min.js',
          './bower_components/angular-route/angular-route.min.js',
          './bower_components/angular-bootstrap/ui-bootstrap.min.js',
          './app/app.js'
        ],
        dest: './build/fusio.js',
      },
    }
  });

  grunt.registerTask('default', ['concat']);

};
