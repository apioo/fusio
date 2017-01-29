module.exports = function(grunt){
  grunt.initConfig({
    concat: {
      dist_js: {
        options: {
          separator: ';\n',
          process: function(src, filepath) {
            return '// Source: ' + filepath + '\n' +
              src.replace(/\/\/# sourceMappingURL=([A-z0-9\-\.\_]+)/g, '').trim();
          }
        },
        src: [
          './node_modules/ace-builds/src-min-noconflict/ace.js',
          './node_modules/ace-builds/src-min-noconflict/mode-javascript.js',
          './node_modules/ace-builds/src-min-noconflict/mode-json.js',
          './node_modules/ace-builds/src-min-noconflict/mode-sql.js',
          './node_modules/ace-builds/src-min-noconflict/mode-xml.js',
          './node_modules/ace-builds/src-min-noconflict/mode-yaml.js',
          './node_modules/ace-builds/src-min-noconflict/worker-javascript.js',
          './node_modules/ace-builds/src-min-noconflict/worker-json.js',
          './dist/fusio-bundle.min.js',
          './dist/fusio-templates.js'
        ],
        dest: './dist/fusio.min.js'
      },
      dist_css: {
        options: {
          separator: '\n',
          process: function(src, filepath) {
            return '/* Source: ' + filepath + ' */\n' +
              src.replace(/\/\/# sourceMappingURL=([A-z0-9\-\.\_]+)/g, '').trim();
          }
        },
        src: [
          './css/bootstrap.min.css',
          './css/bootstrap-theme.min.css',
          './css/highlightjs-github.css',
          './css/angular-loading-bar.min.css',
          './css/ng-tags-input.min.css',
          './css/ng-tags-input.bootstrap.min.css',
          './css/default.css'
        ],
        dest: './dist/fusio.min.css'
      }
    },
    uglify: {
      options: {
        banner: '/*\n fusio\n Copyright (C) 2015-2017 Christoph Kappestein\n License: AGPLv3\n*/\n',
        mangle: false
      },
      dist: {
        files: {
          './dist/fusio-bundle.min.js': [
            './dist/fusio-bundle.js'
          ]
        }
      }
    },
    browserify: {
      dist: {
        files: {
          './dist/fusio-bundle.js': [
            './app/app.js'
          ]
        }
      }
    },
    ngtemplates: {
      fusioApp: {
        src: ['app/controller/*/*.html', 'app/nav-template.html'],
        dest: 'dist/fusio-templates.js',
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
  grunt.loadNpmTasks('grunt-angular-templates');
  grunt.loadNpmTasks('grunt-browserify');

  grunt.registerTask('default', ['browserify', 'ngtemplates', 'uglify', 'concat']);

};
