module.exports = function(grunt){

  grunt.initConfig({
    concat: {
      options: {
        separator: ';\n',
        process: function(src, filepath) {
          return '// Source: ' + filepath + '\n' +
            src.replace(/\/\/# sourceMappingURL=([A-z0-9\-\.\_]+)/g, '').trim();
        },
      },
      dist: {
        src: [
          './bower_components/ace-builds/src-min-noconflict/ace.js',
          './bower_components/ace-builds/src-min-noconflict/mode-json.js',
          './bower_components/ace-builds/src-min-noconflict/mode-sql.js',
          './bower_components/ace-builds/src-min-noconflict/mode-haml.js',
          './bower_components/ace-builds/src-min-noconflict/mode-xml.js',
          './bower_components/ace-builds/src-min-noconflict/worker-json.js',
          './bower_components/Chart.js/Chart.min.js',
          './bower_components/angular/angular.min.js',
          './bower_components/angular-route/angular-route.min.js',
          './bower_components/angular-sanitize/angular-sanitize.min.js',
          './bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
          './bower_components/angular-ui-ace/ui-ace.min.js',
          './bower_components/angular-chart.js/dist/angular-chart.min.js',
          './dist/fusio-app.min.js',
        ],
        dest: './dist/fusio.min.js'
      },
    },
    uglify: {
      options: {
        banner: '/*\n fusio\n Copyright (C) 2015 Christoph Kappestein\n License: GPLv3\n*/\n',
        mangle: false
      },
      dist: {
        files: {
          './dist/fusio-app.min.js': [
            './app/app.js',
            './app/login/login.js',
            './app/logout/logout.js',
            './app/dashboard/dashboard.js',
            './app/routes/routes.js',
            './app/schema/schema.js',
            './app/action/action.js',
            './app/connection/connection.js',
            './app/app/app.js',
            './app/log/log.js',
            './app/user/user.js',
            './app/scope/scope.js',
            './js/FormBuilder.js'
          ]
        }
      }
    },
    cssmin: {
      options: {
        shorthandCompacting: false,
        roundingPrecision: -1,
        rebase: ''
      },
      dist: {
        files: {
          './dist/fusio.min.css': [
            './bower_components/bootstrap/dist/css/bootstrap.css', 
            './bower_components/bootstrap/dist/css/bootstrap-theme.css', 
            './bower_components/angular-chart.js/dist/angular-chart.css', 
            './css/default.css'
          ]
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask('default', ['uglify', 'concat', 'cssmin']);

};
