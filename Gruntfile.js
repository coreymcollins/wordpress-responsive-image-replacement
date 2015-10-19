module.exports = function( grunt ) {

	require('load-grunt-tasks')(grunt);

	var pkg = grunt.file.readJSON( 'package.json' );

	var bannerTemplate = '/**\n' +
		' * <%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
		' * <%= pkg.author.url %>\n' +
		' *\n' +
		' * Copyright (c) <%= grunt.template.today("yyyy") %>;\n' +
		' * Licensed GPLv2+\n' +
		' */\n';

	var compactBannerTemplate = '/** ' +
		'<%= pkg.title %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %> | <%= pkg.author.url %> | Copyright (c) <%= grunt.template.today("yyyy") %>; | Licensed GPLv2+' +
		' **/\n';

	// Project configuration
	grunt.initConfig( {

		pkg: pkg,


		watch:  {
			styles: {
				files: ['assets/**/*.css','assets/**/*.scss'],
				tasks: ['styles'],
				options: {
					spawn: false,
					livereload: true,
					debounceDelay: 500
				}
			},
			scripts: {
				files: ['assets/**/*.js'],
				tasks: ['scripts'],
				options: {
					spawn: false,
					livereload: true,
					debounceDelay: 500
				}
			},
			php: {
				files: ['**/*.php', '!vendor/**.*.php'],
				tasks: ['php'],
				options: {
					spawn: false,
					debounceDelay: 500
				}
			}
		},

		makepot: {
			dist: {
				options: {
					domainPath: '/languages/',
					potFilename: pkg.name + '.pot',
					type: 'wp-plugin'
				}
			}
		},

		addtextdomain: {
			dist: {
				options: {
					textdomain: pkg.name
				},
				target: {
					files: {
						src: ['**/*.php']
					}
				}
			}
		},

		sass: {
			options: {
				sourceMap: true,
				outputStyle: 'expanded',
				lineNumbers: true,
			},
			dist: {
				files: {
					'style.css': 'assets/sass/index.scss'
				}
			}
		},

		cssmin: {
			minify: {
				expand: true,
				cwd: '',
				src: ['*.css', '!*.min.css', '!editor-style.css', '!rtl.css', '!bower_components', '!node_modules'],
				dest: '',
				ext: '.min.css'
			}
		},

		uglify: {
			build: {
				options: {
					mangle: false
				},
				files: [{
					expand: true,
					cwd: 'assets/js/',
					src: ['*.js', '!**/*.min.js', '!concat/*.js', '!vendor/*.js'],
					dest: 'assets/js/',
					ext: '.min.js'
				}]
			}
		},

		jscs: {
			build: {
				options: {
					config: '.jscsrc',
					fix: true,
					force: true,
					// esnext: true, // If you use ES6 http://jscs.info/overview.html#esnext
					// verbose: true, // If you need output with rule names http://jscs.info/overview.html#verbose
					requireCurlyBraces: [ "if" ]
				},
				files: [{
					expand: true,
					cwd: 'assets/js/',
					src: ['*.js', '!**/*.min.js', '!concat/*.js', '!vendor/**/*.js', '!project.js'],
					dest: 'assets/js/',
					ext: '.min.js'
				}]
			}
		},

	} );

	// Default task.
	grunt.registerTask( 'scripts', [ 'jscs', 'uglify' ] );
	grunt.registerTask( 'styles', [ 'sass', 'cssmin' ] );
	grunt.registerTask( 'php', [ 'addtextdomain', 'makepot' ] );
	grunt.registerTask( 'default', ['styles', 'scripts', 'php'] );

	grunt.util.linefeed = '\n';
};
