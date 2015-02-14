module.exports = function(grunt){

  grunt.initConfig({
    concat: {
      options: {
        separator: ';'
      },
      dist: {
        src: [
          './bower_components/ace-builds/src-min-noconflict/ace.js',
          './bower_components/ace-builds/src-min-noconflict/mode-json.js',
          './bower_components/ace-builds/src-min-noconflict/mode-sql.js',
          './bower_components/ace-builds/src-min-noconflict/worker-json.js',
          './bower_components/angular/angular.min.js',
          './bower_components/angular-route/angular-route.min.js',
          './bower_components/angular-sanitize/angular-sanitize.min.js',
          './bower_components/angular-bootstrap/ui-bootstrap-tpls.min.js',
          './bower_components/angular-ui-ace/ui-ace.min.js',
          './dist/fusio.min.js',
        ],
        dest: './dist/fusio.js'
      },
    },
    uglify: {
      options: {
        banner: '/* Fusio - Project */\n',
        mangle: {
          except: ['ace']
        }
      },
      dist: {
        files: {
          './dist/fusio.min.js': [
            './app/app.js',
            './app/login/login.js',
            './app/dashboard/dashboard.js',
            './app/routes/routes.js',
            './app/schema/schema.js',
            './app/action/action.js',
            './app/trigger/trigger.js',
            './app/connection/connection.js',
            './app/app/app.js',
            './app/settings/settings.js',
            './app/log/log.js',
            './app/user/user.js',
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
