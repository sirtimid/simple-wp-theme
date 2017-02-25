module.exports = function(grunt) {

	var npmDependencies = require('./package.json').devDependencies;

	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		// Watches for changes and runs tasks
		watch : {
			sass : {
				files : ['assets/scss/**/*.scss'],
				tasks : ['sass', 'cssmin'],
				options : {
					livereload : true
				}
			},
			js : {
				files : ['assets/js/theme.js'],
				tasks : ['jshint', 'uglify'],
				options : {
					livereload : true
				}
			},
			php : {
				files : ['**/*.php'],
				options : {
					livereload : true
				}
			}
		},

		// concat and minify our JS
		uglify: {
			dist: {
				files: {
					'assets/js/vendors.min.js': [
						'bower_components/fastclick/lib/fastclick.js'
					],
					'assets/js/theme.min.js': [
						'assets/js/theme.js'
					]
				}
			}
		},

		// compile your sass
		sass: {
			dev: {
				options: {
					style: 'expanded',
					sourcemap: false
				},
				files: {
					'assets/css/theme.css': 'scss/theme.scss',
				}
			}
		},

		cssmin: {
			target: {
				files: {
					'assets/css/theme.min.css': [ 'assets/css/theme.css' ]
				}
			}
		},

		// JsHint your javascript
		jshint : {
			all : [
				'assets/js/theme.js'
			],
			options : {
				asi: true,
				browser: true,
				curly: false,
				eqeqeq: false,
				eqnull: true,
				expr: true,
				immed: true,
				newcap: true,
				noarg: true,
				smarttabs: true,
				sub: true,
				undef: false,
				loopfunc: true
			}
		},

	});

	// Load NPM's via matchdep
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// Default task
	grunt.registerTask('default', ['watch']);

	// Build task
	grunt.registerTask('build', [
		'jshint',
		'sass',
		'cssmin',
		'uglify'
	]);

};
