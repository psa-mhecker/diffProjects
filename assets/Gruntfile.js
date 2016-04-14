module.exports = function(grunt) {
	var version = grunt.file.readYAML('../frontend/app/config/assets_version.yml');
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		devicesTarget : 'desktop',
		paths : {
			sources: '.', // location for development source files
			bower_components: 'vendor',
			dist: '../backend/public/media/design/frontend/<%= devicesTarget %>', // location for distributed files,
			tmp: '.tmp'
		},
		concat : {
			options: {
				stripBanners: true,
				banner: '/* NDP - FRONT BUILD <%= grunt.template.today("ddmmyyyy-h:MM:ss") %> */\n',
				sourceMap: true
			},
			//@todo use wildcards but respect order of js
			header_js: {
				src: [
					"<%= paths.bower_components %>/jquery-ui/jquery-ui.js",
					"<%= paths.bower_components %>/modernizr/modernizr.js"
				],
				dest: '<%= paths.dist %>/js/header.js'
			},
			//@todo use wildcards but respect order of js

			vendor_css: {
				src: [
					"<%= paths.dist %>/css/vendor.css",
					"<%= paths.bower_components %>/blueimp-gallery-with-desc/css/blueimp-gallery.min.css",
					"<%= paths.bower_components %>/slick-carousel/slick/slick.css",
					"<%= paths.bower_components %>/slick-carousel/slick/slick-theme.css",
				],
				dest: '<%= paths.dist %>/css/vendor.css'
			},

			vendor_js: {
				src: [
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.orbit.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.dropdown.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.accordion.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.equalizer.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.topbar.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.tooltip.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.interchange.js",
					"<%= paths.bower_components %>/foundation/js/foundation/foundation.tab.js",
					"<%= paths.bower_components %>/underscore/underscore-min.js",
					"<%= paths.bower_components %>/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js",
					"<%= paths.bower_components %>/blueimp-gallery-with-desc/js/blueimp-gallery.min.js",
					"<%= paths.bower_components %>/spin.js/spin.js",
					"<%= paths.bower_components %>/picturefill/dist/picturefill.js",
					"<%= paths.bower_components %>/picturefill/dist/plugins/mutation/pf.mutation.js",
					"<%= paths.bower_components %>/slick-carousel/slick/slick.js",
					"<%= paths.sources %>/js/lib/jquery.event.move.js",
					"<%= paths.sources %>/js/lib/jquery.twentytwenty.js"
					],
				dest: '<%= paths.dist %>/js/vendor.js'
			},
			//@todo use wildcards but respect order of js
			front_js: {
				src: [
					"<%= paths.sources %>/js/components/initJquery.js",
					"<%= paths.sources %>/js/components/nav.js",
					"<%= paths.sources %>/js/components/jquery.initslickcarousel.js",
					"<%= paths.sources %>/js/components/toplink.js",
					"<%= paths.sources %>/js/components/videoPlayer.js",
					"<%= paths.sources %>/js/components/pn7.js",
					"<%= paths.sources %>/js/components/pt2.js",
					"<%= paths.sources %>/js/components/jquery.ndpgtm.js",
					"<%= paths.sources %>/js/components/jquery.showhidetopbar.js",
					"<%= paths.sources %>/js/components/navShowroom.js",
					"<%= paths.sources %>/js/components/anchor.js",
					"<%= paths.sources %>/js/components/jquery.lazyinterchange.js",
					"<%= paths.sources %>/js/components/jquery.lazysrcset.js",
					"<%= paths.sources %>/js/components/jquery.dragndrop.js",
					"<%= paths.sources %>/js/components/popin.js",
					"<%= paths.sources %>/js/components/stickyElement.js",
					"<%= paths.sources %>/js/components/initSlice.js",
					"<%= paths.sources %>/js/main.js"
				],
				dest: '<%= paths.dist %>/js/front.js'
			}
		},
		babel: {
			options: {
				sourceMap: true,
				presets: [require('babel-preset-es2015')]
			},
			dist: {
				files: [
					{
						'<%= paths.sources %>/front.js': '<%= paths.dist %>/js/front.js'
					}
				]
			},
			modules: {
				files: [
					{
						expand: true,
						cwd: '<%= paths.sources %>/js/modules/',
						src: ['**/*.js'],
						dest: '<%= paths.sources %>/modules/'
					}
				]
			},
			test: {
				files: [
					{
						expand: true,
						cwd: '<%= paths.sources %>/test/unit/',
						src: ['**/*.js'],
						dest: '<%= paths.sources %>/test/compiled/'
					},
					{
						expand: true,
						cwd: '<%= paths.sources %>/js/modules/',
						src: ['**/*.js'],
						dest: '<%= paths.sources %>/test/compiled/modules/'
					}
				]
			}
		},
		browserify: {
			options: {
				browserifyOptions: {
					debug: true
				}
			},
			dist: {
				files: {
					'<%= paths.sources %>/fronttmp.js': '<%= paths.sources %>/front.js'
				}
			}
		},
		uglify: {
			dist: {
				options: {
					sourceMap: true,
					sourceMapIn: function(src) {
						return src+'.map';
					}
				},
				files: {
					'<%= paths.dist %>/js/header.js': ['<%= paths.dist %>/js/header.js'],
					'<%= paths.dist %>/js/vendor.js': ['<%= paths.dist %>/js/vendor.js'],
					'<%= paths.dist %>/js/front.js': ['<%= paths.dist %>/js/front.js']
				}
			}
		},
		copy: {
			dist: {
				files: [
					{
						expand: true,
						cwd: '<%= paths.sources %>/img/',
						src: ['**/*.png', '**/*.jpg', '**/*.gif', '**/**.jpeg'],
						dest: '<%= paths.dist %>/img/'
					},
					{
						expand: true,
						cwd: '<%= paths.sources %>/fonts/',
						src: '*',
						dest: '<%= paths.dist %>/fonts/'
					},
					{
						expand: true,
						cwd: "<%= paths.bower_components %>/jquery/dist/",
						src: 'jquery.js',
						dest: '<%= paths.dist %>/js/'
					}
				]
			}
		},
		sprite:{
			all: {
			  src: '<%= paths.sources %>/spritesrc/*.png',
			  retinaSrcFilter: ['<%= paths.sources %>/spritesrc/*@2x.png'],
			  dest: '<%= paths.sources %>/img/sprites/spritesheet-'+version.parameters.assets_version+'.png',
			  destCss: '<%= paths.sources %>/scss/_new_sprites.scss',
			  retinaDest: '<%= paths.sources %>/img/sprites/spritesheet.retina@2x-'+version.parameters.assets_version+'.png'
			}
		},
		imagemin: {
			options: {
				speed: 1,
				progressive: true,
				nofs: true,
				use: [require('imagemin-pngquant')()]
			},
			sprites: {
				files: [
					{
						expand: true,
						src: ['<%= paths.sources %>/img/sprites/*.png'],
						dest: '.'
					}
				]
			},
			img: {
				files: [
					{
						expand: true,
						src: ['<%= paths.sources %>/img/*.{png,jpg,gif}'],
						dest: '.'
					}
				]
			}
		},
		sass: {
			build: {
				options: {
					sourceMap: true,
					outputStyle: 'expanded',
					includePaths: ['<%= paths.bower_components %>/foundation/scss'],
					precision: 8
				},
				files: {
					'<%= paths.dist %>/css/front.css': 'scss/main.scss',
					'<%= paths.dist %>/css/vendor.css': 'scss/vendor.scss'
				}
			}
		},
		postcss: {
			options: {
				map: true,
				processors: [
					require('autoprefixer')({
						browsers: [
							'last 1 version',
							'ie 9'
						]
					}),
					require('cssnano')({
						safe: true
					})
				]
			},
			dist: {
				src: ['<%= paths.dist %>/css/front.css', '<%= paths.dist %>/css/vendor.css']
			}
		},
		concurrent: {
			build: ['concat', 'sass'],
			postbuild: ['uglify:dist', 'postcss:dist'],
			lint: ['jshint', 'scsslint'],
			patterndev: {
				tasks: ['modifywatchdev', 'patternlab'],
				options: {
					logConcurrentOutput: true
				}
			}
		},
		clean: {
			options: {
				force: true
			},
			sprites: 'img/sprites',
			tests: 'test/compiled',
			fronttmp: {
				src: [
					'fronttmp.js',
					'front.js',
					'front.js.map',
					'modules/'
				]
			}
		},
		watch: {
			js: {
				files: 'js/**/*.js',
				tasks: ['concat', 'babelify']
			},
			css: {
				files: 'scss/**/*.scss',
				tasks: ['sass', 'concat:vendor_css', 'postcss:dist']
			},
			jshint: {
				files: 'js/**/*.js',
				tasks: ['jshint'],
				options: {
					spawn: false
				}
			},
			scsslint: {
				files: 'scss/**/*.scss',
				tasks: ['scsslint'],
				options: {
					spawn: false
				}
			}
		},
		scsslint: {
			all: [
				'./scss/**/*.scss'
			],
			options: {
				config: '.scss-lint.yml',
				exclude: ['./scss/vendor/**/*.scss'],
				force: true,
				compact: true,
				maxBuffer: 500 * 1024 //TODO restore to default when error count permits it
			}
		},
		jshint: {
			options: {
				jshintrc: true,
				force: true
			},
			all: ['Gruntfile.js', './js/**/*.js', '!./js/ie/jplayer/*.js', '!./js/lib/*.js']
		},
		karma: {
			unit: {
				configFile: 'karma.conf.js'
			}
		}
	});

	// on watch events configure jshint:all and scsslint:all to only run on changed file
	grunt.event.on('watch', function(action, filepath, target) {
		switch(target) {
			case 'jshint':
				grunt.config('jshint.all', filepath);
				break;
			case 'scsslint':
				grunt.config('scsslint.all', filepath);
				break;
		}
	});

	// load all grunt tasks
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

	// Build
	grunt.registerTask('default', ['concurrent:build', 'babelify', 'concurrent:postbuild','copy:dist']);

	// Development
	grunt.registerTask('dev', ['concurrent:build', 'babelify', 'postcss:dist', 'watch']);

	// Lint
	grunt.registerTask('lint', ['concurrent:lint']);

	// Build for composer
	grunt.registerTask('build', ['clean:sprites', 'sprite', 'imagemin:sprites', 'imagemin:img', 'concurrent:build', 'babelify', 'concurrent:postbuild', 'copy:dist']);

	// Regenerate spritesheet
	grunt.registerTask('sprites', ['clean:sprites', 'sprite', 'imagemin:sprites', 'copy:dist']);

	// Dev with patternlab
	grunt.registerTask('patterndev', ['concurrent:patterndev']);

	// Dev with modify-watch
	grunt.registerTask('modifywatchdev', [ 'modify-watch', 'dev']);

	// Babelify
	grunt.registerTask('babelify', [ 'babel:modules', 'configure-babel', 'babel:dist', 'browserify:dist', 'merge-sourcemaps']);

	// Test
	grunt.registerTask('test', [ 'clean:tests', 'babel:test', 'karma']);

	// helper function to spawn grunt tasks in patternlab
	var patternLabSpawn = function(tasks) {
	  return function() {
		  var cb = this.async();
		  var child = grunt.util.spawn({
			  grunt: true,
			  args: tasks,
			  opts: {
				  cwd: 'patternlab'
			  }
		  }, function() {
			  cb();
		  });

		  child.stdout.pipe(process.stdout);
		  child.stderr.pipe(process.stderr);
	  };
	};

	// Launch patternlab serve task
	grunt.registerTask('patternlab', patternLabSpawn(['serve']));

	// Launch patternlab sass task
	grunt.registerTask('patternlab-sass', patternLabSpawn(['sass']));

	// Launch patternlab copy task
	grunt.registerTask('patternlab-copy', patternLabSpawn(['copy']));

	// Trigger a livereload for patternlab
	grunt.registerTask('livereload-trigger', function() {
		var done = this.async();
		require('http').get('http://localhost:35729/changed?files=all', done);
	});

	// Modify watcher for patternlab
	grunt.registerTask('modify-watch', function() {
		grunt.config.set('watch.css.tasks', ['sass', 'postcss:dist', 'patternlab-sass', 'livereload-trigger']);
		grunt.config.set('watch.js.tasks', ['concat', 'babelify', 'patternlab-copy']);
	});

	grunt.task.registerTask("configure-babel", "configures babel options", function() {
		if (grunt.file.exists(grunt.config.get('paths.dist')+'/js/front.js.map')) {
			grunt.config.set('babel.options.inputSourceMap', grunt.file.readJSON(grunt.config.get('paths.dist')+'/js/front.js.map'));
		}
	});

	grunt.task.registerTask("merge-sourcemaps", "merges babelify and browserify sourcemaps", function() {
		var sorcery = require("sorcery");

		var files = grunt.config.get("concat.front_js.src");

		var content = {};

		files.forEach(function(file) {
			content[file.replace(/^\.\//, '../../../../../../../assets/')] = grunt.file.read(file);
		});

		grunt.file.recurse(grunt.config.get("paths.sources")+'/js/modules/', function(file) {
			if(/\.js$/.test(file)) {
				content[file] = grunt.file.read(file);
			}
		});

		var chain = sorcery.loadSync( grunt.config.get("paths.sources")+"/fronttmp.js" , {
			content: content
		});
		var map = chain.apply();
		grunt.task.run('clean:fronttmp');

		chain.writeSync( grunt.config.get("paths.dist")+'/js/front.js' );
	});
};
