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
			files: {
				'admin/js/admin.js': ['admin/scripts/admin.js'],
				'admin/js/themer.js': ['admin/scripts/themer.js'],
				'admin/js/widgets-page.js': ['admin/scripts/widgets-page.js'],
				'admin/js/jquery.arcw.js': ['admin/scripts/jquery.arcw.js'],
				'admin/js/jquery.arcw-init.js': ['admin/scripts/jquery.arcw.js', 'admin/scripts/jquery.arcw-init.js']
			}
		},

		uglify: {
			options: {
				mangle: true
			},
			scripts: {
				files: {
					'admin/js/admin.js': ['admin/scripts/admin.js'],
					'admin/js/themer.js': ['admin/scripts/themer.js'],
					'admin/js/widgets-page.js': ['admin/scripts/widgets-page.js'],
					'admin/js/jquery.arcw.js': ['admin/scripts/jquery.arcw.js'],
					'admin/js/jquery.arcw-init.js': ['admin/scripts/jquery.arcw.js', 'admin/scripts/jquery.arcw-init.js']
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
		'concat',
		'watch'
	]);
	grunt.registerTask('build', [
		'compass:dist',
		'compass:themes',
		'uglify'
	]);

	grunt.registerTask('release', ['build', 'newer:copy:release']);
};