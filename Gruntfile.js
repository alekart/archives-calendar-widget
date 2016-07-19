// Generated on 2015-11-16 using generator-angular 0.14.0
'use strict';

// # Globbing

module.exports = function (grunt) {

	// Automatically load required Grunt tasks
	require('jit-grunt')(grunt);

	grunt.initConfig({

		watch: {
			compass: {
				files: ['admin/scss/{,**/}*.scss', 'themes/scss/{,**/}*.scss'],
				tasks: ['compass:dev', 'compass:themes']
			},
			scripts: {
				files: ['admin/scripts/{,**/}*.js'],
				tasks: ['uglify']
			},
			livereload: {
				options: {livereload: false},
				files: ['{,**/}*.css', 'scripts/*.js']
			}
		},

		// Compiles Sass to CSS and generates necessary files if requested
		compass: {
			options: {
				importPath: './bower_components'
			},
			dist: {
				options: {
					sassDir: 'admin/scss',
					cssDir: 'admin/css/',
					environment: 'production'
				}
			},
			dev: {
				options: {
					sassDir: 'admin/scss',
					cssDir: 'admin/css/'
				}
			},
			themes: {
				options: {
					sassDir: 'themes/scss',
					cssDir: 'themes/',
					environment: 'production'
				}
			}
		},

		concat: {
			options: {
				separator: ';'
			},
			lib: {
				src: ['./scripts/lib/{,**/}*.js'],
				dest: './scripts/lib.js'
			},
			main: {
				src: ['./scripts/lib.js', './scripts/main.js'],
				dest: './js/main.js'
			}
		},

		uglify: {
			options: {
				mangle: false
			},
			scripts: {
				files: {
					'admin/js/admin.min.js': ['admin/scripts/admin.js'],
					'admin/js/themer.min.js': ['admin/scripts/themer.js'],
					'admin/js/widgets-page.min.js': ['admin/scripts/widgets-page.js'],
					'admin/js/jquery.arcw.min.js': ['admin/scripts/jquery.arcw.js'],
					'admin/js/jquery.arcw-init.min.js': ['admin/scripts/jquery.arcw.js', 'admin/scripts/jquery.arcw-init.js']
				}
			}
		},

		modernizr: {
			dist: {
				options: [
					"setClasses"
				],
				tests: [
					"placeholder",
					"appearance"
				],
				crawl: false,
				dest: "scripts/vendor/modernizr.js"
			}
		},

		copy: {
			release: {
				expand: true,
				cwd: '.',
				src: [
					'**',
					'!.sass_cache/**',
					'!bower_components/**',
					'!node_modules/**',
					'!scripts/**',
					'!scss/**',
					'!*.{js,scss,json}'
				],
				dest: 'dist/'
			}
		}

	});

	grunt.loadNpmTasks("grunt-modernizr");
	// grunt.loadNpmTasks("grunt-contrib-copy");

	grunt.registerTask('default', [
		'compass:dev',
		// 'concat',
		'uglify',
		'watch'
	]);
	grunt.registerTask('build', [
		'compass:dist',
		'compass:themes',
		// 'concat',
		'uglify'
	]);

	grunt.registerTask('release', ['build', 'newer:copy:release']);
};