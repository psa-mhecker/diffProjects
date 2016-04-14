module.exports = function(grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        paths : {
            bower_components: '../vendor',
            source: 'source', // location for development source files
            dist: 'public', // location for distributed files,
            ndp: '../../backend/public/media/design/frontend/desktop', // location for NDP files,
            tmp: '.tmp'
        },
        clean: {
			options: { force: true },
			files: ['./<%= paths.dist %>/patterns']
		},
        concat: {
            options: {
                stripBanners: true,
                banner: '/* \n * <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy") %> \n * \n * <%= pkg.author %>, and the web community.\n * Licensed under the <%= pkg.license %> license. \n * \n * Many thanks to Brad Frost and Dave Olsen for inspiration, encouragement, and advice. \n *\n */\n\n'
            },
            patternlab: {
                src: './builder/patternlab.js',
                dest: './builder/patternlab.js'
            },
            object_factory: {
                src: './builder/object_factory.js',
                dest: './builder/object_factory.js'
            },
            lineage: {
                src: './builder/lineage_hunter.js',
                dest: './builder/lineage_hunter.js'
            },
            media_hunter: {
                src: './builder/media_hunter.js',
                dest: './builder/media_hunter.js'
            },
            patternlab_grunt: {
                src: './builder/patternlab_grunt.js',
                dest: './builder/patternlab_grunt.js'
            },
            parameter_hunter: {
                src: './builder/parameter_hunter.js',
                dest: './builder/parameter_hunter.js'
            },
            pattern_exporter: {
                src: './builder/pattern_exporter.js',
                dest: './builder/pattern_exporter.js'
            },
            pattern_assembler: {
                src: './builder/pattern_assembler.js',
                dest: './builder/pattern_assembler.js'
            },
            pseudopattern_hunter: {
                src: './builder/pseudopattern_hunter.js',
                dest: './builder/pseudopattern_hunter.js'
            }
        },
		copy: {
			main: {
				files: [
                    // Common served files
                    { expand: true, cwd: '<%= paths.ndp %>/js/', src: '**/*.*', dest: './<%= paths.dist %>/js/'},
                    { expand: true, cwd: './<%= paths.source %>/css/', src: '**/*.css', dest: './<%= paths.dist %>/css/'},
                    { expand: true, cwd: './<%= paths.source %>/images/', src: ['**/*.png', '**/*.jpg', '**/*.gif', '**/**.jpeg'], dest: './<%= paths.dist %>/images/'},
                    { expand: true, cwd: './<%= paths.source %>/images/sample', src: ['**/*.png', '**/*.jpg', '**/*.gif', '**/**.jpeg'], dest: './<%= paths.dist %>/images/sample'},
                    { expand: true, cwd: '<%= paths.ndp %>/img/', src: ['**/*.png', '**/*.jpg', '**/*.gif', '**/**.jpeg'], dest: './<%= paths.dist %>/img/'},
                    { expand: true, cwd: '<%= paths.ndp %>/fonts/', src: '*', dest: './<%= paths.dist %>/fonts/'},
                    { expand: true, cwd: './<%= paths.source %>/_data/', src: 'annotations.js', dest: './<%= paths.dist %>/data/'}
				]
			}
		},
        jshint: {
            files: ['../js/*.js'],
            options: {
                "curly": true,
                "eqnull": true,
                "eqeqeq": true,
                "undef": true,
                "forin": true,
                //"unused": true,
                "node": true
            },
            patternlab: ['Gruntfile.js', './builder/patternlab.js']
        },
		watch: {
            scss: { //scss can be watched if you like
                options:{
                    livereload: true
                },
                files: [
                    '<%= paths.source %>/scss/**/*.scss',
                    './<%= paths.dist %>/styleguide/scss/*.scss'
                ],
                tasks: ['sass']
            },
            all: {
                options:{
                    livereload: true
                },
                files: [
                    '<%= paths.source %>/css/**/*.css',
                    '<%= paths.dist %>/styleguide/css/*.css',
                    '<%= paths.source %>/_patterns/**/*.mustache',
                    '<%= paths.source %>/_patterns/**/*.json',
                    '<%= paths.source %>/_data/*.json',
                    '../js/**/*.js'
                ],
                tasks: ['default']
            }
		},
        sass: {
            build: {
                options: {
                    style: 'expanded',
                    loadPath: ['<%= paths.bower_components %>/foundation/scss'],
                    precision: 8
                },
                files: {
                    './public/css/main.css': './<%= paths.source %>/scss/main.scss',
                    './public/styleguide/css/styleguide.css': './<%= paths.dist %>/styleguide/css/styleguide.scss',
                    './public/styleguide/css/styleguide-specific.css': './<%= paths.dist %>/styleguide/css/styleguide-specific.scss'
                }
            }
        },
        sprite:{
            all: {
                src: './<%= paths.source %>/spritesrc/*.png',
                dest: './<%= paths.source %>/img/sprites/spritesheet.png',
                destCss: './<%= paths.source %>/scss/_new_sprites.scss'
            }
        },
        nodeunit: {
            all: ['test/*_tests.js']
        },
        connect: {
			app:{
				options: {
					port: 9001,
					base: './public',
					hostname: 'localhost',
					open: 'http://localhost:9001/',
					livereload: 35729,
                    middleware: function (connect, options, middlewares) {
                        // inject a custom middleware
                        middlewares.unshift(function (req, res, next) {
                            res.setHeader('Access-Control-Allow-Origin', '*');
                            res.setHeader('Access-Control-Allow-Methods', '*');
                            return next();
                        });

                        return middlewares;
                    }
				}
			}
		}
    });

// load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    //load the patternlab task
    grunt.task.loadTasks('./builder/');

    grunt.registerTask('default', ['clean', 'concat', 'patternlab', 'sass', 'copy']);

    grunt.registerTask('serve', ['clean', 'concat', 'patternlab', 'sass', 'copy', 'connect', 'watch']);

    grunt.registerTask('build', ['clean', 'concat', 'patternlab', 'copy']);

};
