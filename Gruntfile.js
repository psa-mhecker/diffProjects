module.exports = function(grunt) {

	var pathAssets = 'public/media/design/frontend/assets/',
		pathOutput = 'public/media/design/frontend/';
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
      /*
        CONCATENATION JAVASCRIPT NON MINIFIEE
      */
      concat: {
        options: {
          separator: ';',
        },
        WEBcitroenJs: {
          src: [pathAssets+'js/jquery.fancybox-media.js',pathAssets+'js/main.js', pathAssets+'js/applications.js'],
          dest: pathOutput+'js/citroen.js'
        },
        /*WEBcitroenJsVendor: {
          src: [pathAssets+'js/vendor/!**!/!*.js'],
          dest: pathOutput+'js/vendor.js',
        },*/
        WEBjQueryTools: {
          src: [pathAssets+'js/underscore-min.js', pathAssets+'js/jquery.fancybox.pack.js',pathAssets+'js/jquery.fancybox-media.js',pathAssets+'js/jquery.bxslider.min.js',pathAssets+'js/jquery.lazyload.min.js',pathAssets+'js/jquery.mousewheel.min.js',pathAssets+'js/jquery.masonry.min.js',pathAssets+'js/jquery.jscrollpane.min.js',pathAssets+'js/jquery-ui.js',pathAssets+'js/jquery.functions.js'],
          dest: pathOutput+'js/jquery.tools.min.js'
        },
		
		WEBjsPsaTools: {
          src: [pathAssets+'js/common/cookiesbanner.js', pathAssets+'js/common/modernizr-custom.js',pathAssets+'js/common/class/mqdetector.js',pathAssets+'js/common/class/touchdetect.js',pathAssets+'js/common/easyTab.js',pathAssets+'js/common/main.js',pathAssets+'js/common/nav-desktop.js',pathAssets+'js/common/toolbar.js',pathAssets+'js/common/scrolldown.js'],
          dest: pathOutput+'js/function.psa.js'
        },
		
        WEBlocator: {
          src: [pathAssets+'js/locator.js'],
          dest: pathOutput+'js/locator.js'
        },
        WEBgmap: {
          src: [pathAssets+'js/google.maps.markerclusterer.v3.min.js'],
          dest: pathOutput+'js/google.maps.markerclusterer.v3.min.js'
        },
        MOBILECitroenJs: {
          src: [pathAssets+'js/mobile/lib/underscore-min.js',pathAssets+'js/mobile/locator.js',pathAssets+'js/mobile/main.js', pathAssets+'js/mobile/applications.js'],
          dest: pathOutput+'js/mobile/citroen.js'
        },
        MOBILEjQueryTools: {
          src: [pathAssets+'js/mobile/jquery.nouislider.min.js',pathAssets+'js/mobile/lib/jquery.touchswipe.min.js',pathAssets+'js/jquery.masonry.min.js',pathAssets+'js/jquery.functions.js'],
          dest: pathOutput+'js/mobile/jquery.tools.min.js'
        },
        MOBILEgmap: {
          src: [pathAssets+'js/mobile/google.maps.markerclusterer.v3.min.js'],
          dest: pathOutput+'js/mobile/google.maps.markerclusterer.v3.min.js'
        },
        MOBILEjsTools: {
          src: [pathAssets+'js/mobile/gestures.min.js', pathAssets+'js/mobile/jquery.bxslider.min.js', pathAssets+'js/mobile/jquery.lazyload.min.js', pathAssets+'js/mobile/typeahead.min.js', pathAssets+'js/mobile/iscroll-lite.js'],
          dest: pathOutput+'js/mobile/js.tools.min.js'
        }
      },
      /*
        CONCATENATION JAVASCRIPT MINIFIEE
      */
      uglify: {
        options: {
          separator: ';'
		  ,preserveComments: 'some'
	 	  ,compress: {
				drop_console: true
			}
		  ,mangle: false
        },
        WEBcitroenJs: {
          src: [pathAssets+'js/jquery.fancybox-media.js',pathAssets+'js/main.js', pathAssets+'js/applications.js'],
          dest: pathOutput+'js/citroen.js'
        },
        /*WEBcitroenJsVendor: {
          src: [pathAssets+'js/vendor/!**!/!*.js'],
          dest: pathOutput+'js/vendor.js',
        },*/
        WEBjQueryTools: {
          src: [pathAssets+'js/underscore-min.js', pathAssets+'js/jquery.fancybox.pack.js',pathAssets+'js/jquery.fancybox-media.js',pathAssets+'js/jquery.bxslider.min.js',pathAssets+'js/jquery.lazyload.min.js',pathAssets+'js/jquery.mousewheel.min.js',pathAssets+'js/jquery.masonry.min.js',pathAssets+'js/jquery.jscrollpane.min.js',pathAssets+'js/jquery-ui.js',pathAssets+'js/jquery.functions.js'],
          dest: pathOutput+'js/jquery.tools.min.js'
        },
        WEBlocator: {
          src: [pathAssets+'js/locator.js'],
          dest: pathOutput+'js/locator.js'
        },
        WEBgmap: {
          src: [pathAssets+'js/google.maps.markerclusterer.v3.min.js'],
          dest: pathOutput+'js/google.maps.markerclusterer.v3.min.js'
        },
		WEBjsPsaTools: {
          src: [pathAssets+'js/common/cookiesbanner.js', pathAssets+'js/common/modernizr-custom.js',pathAssets+'js/common/class/mqdetector.js',pathAssets+'js/common/class/touchdetect.js',pathAssets+'js/common/easyTab.js',pathAssets+'js/common/main.js',pathAssets+'js/common/nav-desktop.js',pathAssets+'js/common/toolbar.js',pathAssets+'js/common/scrolldown.js'],
          dest: pathOutput+'js/function.psa.js'
        },
        MOBILECitroenJs: {
          src: [pathAssets+'js/mobile/locator.js',pathAssets+'js/mobile/main.js', pathAssets+'js/mobile/applications.js'],
          dest: pathOutput+'js/mobile/citroen.js'
        },
        MOBILEjQueryTools: {
          src: [pathAssets+'js/mobile/jquery.nouislider.min.js',pathAssets+'js/mobile/lib/jquery.touchswipe.min.js',pathAssets+'js/mobile/lib/underscore-min.js',pathAssets+'js/jquery.masonry.min.js'],
          dest: pathOutput+'js/mobile/jquery.tools.min.js'
        },
        MOBILEgmap: {
          src: [pathAssets+'js/mobile/google.maps.markerclusterer.v3.min.js'],
          dest: pathOutput+'js/mobile/google.maps.markerclusterer.v3.min.js'
        },
        MOBILEjsTools: {
          src: [pathAssets+'js/mobile/gestures.min.js', pathAssets+'js/mobile/jquery.bxslider.min.js', pathAssets+'js/mobile/jquery.lazyload.min.js', pathAssets+'js/mobile/typeahead.min.js', pathAssets+'js/mobile/iscroll-lite.js'],
          dest: pathOutput+'js/mobile/js.tools.min.js'
        }
      },
      /*
        CONCATENATION CSS MINIFIEE
      */
      cssmin: {
    		options: {
    			shorthandCompacting: false,
    			roundingPrecision: -1,
                restructuring : false
    		},
    		WEBtarget: {
    		    files: {
    		      'public/media/design/frontend/assets/css/output.css': [pathAssets+'css/reset.css', pathAssets+'css/jquery-ui.css', pathAssets+'css/videojs.css', pathAssets+'css/main-dev.css', pathAssets+'css/responsive.css', pathAssets+'css/sprites.css', pathAssets+'css/stylesheet.css', pathAssets+'css/font.css', pathAssets+'css/main.css']
    		    }
    		},
    		MOBILEtarget: {
    		    files: {
    		      'public/media/design/frontend/css/mobile/main.css': [pathAssets+'css/mobile/reset.css', pathAssets+'css/mobile/jquery-ui.css', pathAssets+'css/mobile/swipebox.css', pathAssets+'css/mobile/main.css', pathAssets+'css/mobile/stylesheet.css', pathAssets+'css/mobile/font.css', pathAssets+'css/mobile/main-mobile.css']
    		    }
    		}
      },
      bless:{
      	WEBcss:{
    			options: {},
    			files: {
    			'public/media/design/frontend/css/main.css': pathAssets+'css/output.css'
    			}
      	}
      },

      watch: {
        options: {
          livereload: false, // Activons le livereload du navigateur
        },
        src: {
          files: [pathAssets+'js/**/*.js', pathAssets+'css/**/*.css'], // Les fichiers à observer…
          tasks: ['dev'], // … la commande à effectuer
        }
      }


    });

      // 2. Je charge ma tâche
      grunt.loadNpmTasks('grunt-contrib-concat');
      grunt.loadNpmTasks('grunt-contrib-uglify');
      grunt.loadNpmTasks('grunt-contrib-cssmin');
      grunt.loadNpmTasks('grunt-bless');
      grunt.loadNpmTasks('grunt-contrib-watch');

      // J'assigne ma tâche à la commande par défaut de Grunt
      grunt.registerTask('default', ['concat:WEBcitroenJs','concat:WEBjQueryTools','concat:WEBjsPsaTools','concat:WEBlocator','concat:WEBgmap','concat:MOBILECitroenJs','concat:MOBILEjQueryTools','concat:MOBILEgmap','concat:MOBILEjsTools','cssmin:WEBtarget','cssmin:MOBILEtarget','bless:WEBcss']);
      grunt.registerTask('dev', ['concat:WEBcitroenJs','concat:WEBjQueryTools','concat:WEBjsPsaTools','concat:WEBlocator','concat:WEBgmap','concat:MOBILECitroenJs','concat:MOBILEjQueryTools','concat:MOBILEgmap','concat:MOBILEjsTools','cssmin:WEBtarget','cssmin:MOBILEtarget','bless:WEBcss']);
      grunt.registerTask('deliver', ['uglify:WEBcitroenJs','uglify:WEBjQueryTools','uglify:WEBjsPsaTools','uglify:WEBlocator','uglify:WEBgmap','uglify:MOBILECitroenJs','uglify:MOBILEjQueryTools','uglify:MOBILEgmap','uglify:MOBILEjsTools','cssmin:WEBtarget','cssmin:MOBILEtarget','bless:WEBcss']);
};