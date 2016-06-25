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
          './bower_components/showdown/dist/showdown.min.js',
          './bower_components/angular/angular.min.js',
          './bower_components/angular-route/angular-route.min.js',
          './bower_components/angular-sanitize/angular-sanitize.min.js',
          './bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
          './bower_components/angular-ui-ace/ui-ace.min.js',
          './bower_components/angular-chart.js/dist/angular-chart.min.js',
          './bower_components/ng-showdown/dist/ng-showdown.min.js',
          './dist/fusio-app.min.js',
          './dist/fusio-templates.min.js'
        ],
        dest: './dist/fusio.min.js'
      },
    },
    uglify: {
      options: {
        banner: '/*\n fusio\n Copyright (C) 2015-2016 Christoph Kappestein\n License: AGPLv3\n*/\n',
        mangle: false
      },
      dist: {
        files: {
          './dist/fusio-app.min.js': [
            './app/app.js',
            './app/account/change_password.js',
            './app/action/action.js',
            './app/app/app.js',
            './app/config/config.js',
            './app/connection/connection.js',
            './app/dashboard/dashboard.js',
            './app/import/import.js',
            './app/log/log.js',
            './app/login/login.js',
            './app/logout/logout.js',
            './app/routes/routes.js',
            './app/schema/schema.js',
            './app/scope/scope.js',
            './app/statistic/statistic.js',
            './app/user/user.js',
            './js/FormBuilder.js',
            './js/HelpLoader.js'
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
    },
    ngtemplates: {
      fusioApp: {
        src: 'app/*/*.html',
        dest: 'dist/fusio-templates.min.js',
        options: {
          htmlmin: {
            collapseBooleanAttributes: true,
            collapseWhitespace: true,
            removeAttributeQuotes: true,
            removeComments: true,
            removeEmptyAttributes: true,
            removeRedundantAttributes: true,
            removeScriptTypeAttributes: true,
            removeStyleLinkTypeAttributes: true
          }
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-angular-templates');

  grunt.registerTask('default', ['uglify', 'ngtemplates', 'concat', 'cssmin']);

};
