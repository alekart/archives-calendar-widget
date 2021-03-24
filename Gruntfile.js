// Generated on 2015-11-16 using generator-angular 0.14.0
'use strict';

// # Globbing

module.exports = function (grunt) {

  // Automatically load required Grunt tasks
	require('jit-grunt')(grunt);

	var packages = grunt.file.readJSON('package.json'),
		changelog = grunt.file.read('CHANGELOG.md');

	var jsFiles = {
		'<%= paths.dist %>/admin/js/admin.js': ['<%= paths.src %>/admin/scripts/admin.js'],
			'<%= paths.dist %>/admin/js/themer.js': ['<%= paths.src %>/admin/scripts/themer.js'],
			'<%= paths.dist %>/admin/js/widgets-page.js': ['<%= paths.src %>/admin/scripts/widgets-page.js'],
			'<%= paths.dist %>/admin/js/jquery.arcw.js': ['<%= paths.src %>/admin/scripts/jquery.arcw.js'],
			'<%= paths.dist %>/admin/js/jquery.arcw-init.js': [
			'<%= paths.src %>/admin/scripts/jquery.arcw.js',
			'<%= paths.src %>/admin/scripts/jquery.arcw-init.js'
		]
	};

	grunt.initConfig({

		packages: packages,
		changelog: changelog,

		paths: {
			src: "src",
			dist: "dist",
			sass: "src/admin/scss",
			themes: "src/themes/scss"
		},

		watch: {
			sass: {
				files: ['<%= paths.sass %>/{,**/}*.scss'],
				tasks: ['sass:dev']
			},

			themes: {
				files: ['<%= paths.themes %>/{,**/}*.scss'],
				tasks: ['sass:themes']
			},

			files: {
				files: ['<%= paths.src %>/**/*.{png,svg,jpg,php,txt,css,mo}'],
				tasks: ['newer:copy:release']
			},
			scripts: {
				files: ['<%= paths.src %>/admin/scripts/{,**/}*.js'],
				tasks: ['concat']
			},
			livereload: {
				options: {livereload: false},
				files: ['<%= paths.dist %>/**/*.css', '<%= paths.dist %>/**/*.js']
			}
		},

		// Compiles Sass to CSS and generates necessary files if requested
		sass: {
			options: {
        loadPath: ['./node_modules/'],
			},
			dev: {
        files: [{
          expand: true,
          cwd: '<%= paths.sass %>',
          src: ['*.scss'],
          dest: '<%= paths.dist %>/admin/css/',
          ext: '.css',
        }, {
          expand: true,
          cwd: '<%= paths.themes %>',
          src: ['*.scss'],
          dest: '<%= paths.dist %>/themes/',
          ext: '.css',
        }],
      },
			dist: {
				options: {
					style: 'compressed'
        },
        files: [{
          expand: true,
          cwd: '<%= paths.sass %>',
          src: ['*.scss'],
          dest: '<%= paths.dist %>/admin/css/',
          ext: '.css',
        }],
      },
			themes: {
				options: {
					style: 'compressed'
				},
        files: [{
          expand: true,
          cwd: '<%= paths.themes %>',
          src: ['*.scss'],
          dest: '<%= paths.dist %>/themes/',
          ext: '.css',
        }],
			}
		},

		concat: {
			js: {
				files: jsFiles
			}
		},

		uglify: {
			options: {
				mangle: true
			},
			scripts: {
				files: jsFiles
			}
		},

		copy: {
			release: {
				expand: true,
				cwd: '<%= paths.src %>/',
				src: [
					'**/*.*',
					'!admin/scripts/**',
					'!admin/scss/**'
				],
				dest: '<%= paths.dist %>/'
			}
		},

		replace: {
			debugDisable: {
				options: {
					patterns: [
						{
							match: /define\( 'ARCW_DEBUG', true \);/g,
							replacement: "define( 'ARCW_DEBUG', false );"
						}
					]
				},
				files: [
					{expand: true, flatten: true, src: ['<%= paths.dist %>/archives-calendar.php'], dest: 'dist/'}
				]
			},
			debugRemove: {
				options: {
					patterns: [
						{
							match: /debug[ ]?\(.+\);/g,
							replacement: ""
						}
					]
				},
				files: [
					{
						expand: true,
						flatten: true,
						cwd: '<%= paths.dist %>/',
						src: ['*.php'],
						dest: '<%= paths.dist %>'
					}
				]
			},
			version: {
				options: {
					patterns: [
						{
							match: "version",
							replacement: "<%= packages.version %>"
						}
					]
				},
				files: [
					{
						expand: true,
						flatten: true,
						cwd: '<%= paths.dist %>/',
						src: ['*.{php,txt}'],
						dest: '<%= paths.dist %>'
					}
				]
			},
			changelog: {
				options: {
					patterns: [{
						match: "changelog",
						replacement: "<%= changelog %>"
					}]
				},
				files: [
					{
						expand: true,
						flatten: true,
						src: ['<%= paths.dist %>/readme.txt'],
						dest: '<%= paths.dist %>/'
					}
				]
			},
			changelogTitles: {
				options: {
					patterns: [{
						match: /[#][ ]?(Changelog)/g,
						replacement: "== $1 =="
					},{
						match: /[#]{2}[ ]?(.+)/g,
						replacement: "= $1 ="
					}]
				},
				files: [
					{
						expand: true,
						flatten: true,
						src: ['<%= paths.dist %>/readme.txt'],
						dest: '<%= paths.dist %>/'
					}
				]
			}
		},


		clean: {
			dist: ['<%= paths.dist %>'],
			js: ['<%= paths.dist %>/admin/js/*.js']
		}

	});

	grunt.registerTask('default', '', function(){
		grunt.fatal('Use "serve", "build" or "release" tasks');
	});

	grunt.registerTask('serve', [
		'sass:dev',
		'clean:js',
		'concat',
		'newer:copy:release',
		'replace:version',
		'watch'
	]);


	grunt.registerTask('build', [
		'clean:dist',
		'sass:dist',
		'sass:themes',
		'uglify',
		'copy:release',
		'replace'
	]);

	grunt.registerTask('release', ['build']);
};
