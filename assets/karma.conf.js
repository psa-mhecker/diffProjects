// Karma configuration
// Generated on Wed Jan 13 2016 14:01:41 GMT+0100 (CET)

module.exports = function(config) {
  config.set({

    // base path that will be used to resolve all patterns (eg. files, exclude)
    basePath: '',


    // frameworks to use: jasmine or mocha
    // available frameworks: https://npmjs.org/browse/keyword/karma-adapter
    frameworks: ['browserify', 'jasmine'],

    // list of files / patterns to load in the browser
    files: [
      'vendor/jquery/dist/jquery.min.js',
      'vendor/underscore/underscore-min.js',
      'vendor/spin.js/spin.js',
      'node_modules/jasmine-ajax/lib/mock-ajax.js',
      // 'js/*.js',
      // 'js/**/*.js',
      'test/compiled/*.js'
    ],

    // list of files to exclude
    exclude: [
      '**/*swp',
      'js/main.js',
      'js/ie/scripts.js',
    ],

    // preprocess matching files before serving them to the browser
    // available preprocessors: https://npmjs.org/browse/keyword/karma-preprocessor
    preprocessors: {
      'js/*.js': ['coverage'],
      'test/compiled/*.js': ['browserify']
    },

    // add proxy for forms calls
    proxies: {
        '/forms/v2': {
            'target': 'http://fr.psa-ndp.com:80/forms/v2',
            'changeOrigin': true
        },
        '/services/getflux': 'http://fr.psa-ndp.com:80/services/getflux',
        '/version/vc': 'http://fr.psa-ndp.com:80/version/vc'
    },

    // test results reporter to use
    // possible values: 'dots', 'progress'
    // available reporters: https://npmjs.org/browse/keyword/karma-reporter
    reporters: ['dots', 'coverage'],


    // web server port
    port: 9876,


    // enable / disable colors in the output (reporters and logs)
    colors: true,


    // level of logging
    // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
    logLevel: config.LOG_INFO,


    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: false,


    // start these browsers
    // available browser launchers: https://npmjs.org/browse/keyword/karma-launcher
    browsers: ['Chrome'/*, 'Firefox'*/],


    // Continuous Integration mode
    // if true, Karma captures browsers, runs the tests and exits
    singleRun: true,

    // Concurrency level
    // how many browser should be started simultaneous
    concurrency: Infinity,


    // optionally, configure the reporter
    coverageReporter: {
      type : 'html',
      dir : 'test/report/coverage/'
    }

  })
}
