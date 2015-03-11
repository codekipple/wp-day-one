module.exports = function(grunt) {

    /*
        Use load-grunt-config module to load config
        files that are in sepaerate folders
        --------------------------------------------------------------------
    */
    var path = require('path');

    var config = require('load-grunt-config')(grunt, {
        configPath: path.join(process.cwd(), 'grunt/options'), //path to task.js files, defaults to grunt dir
        init: false, // don't auto load grunt config
        loadGruntTasks: { //can optionally pass options to load-grunt-tasks.
            pattern: 'grunt-*',
            config: require('./package.json'),
            scope: 'devDependencies'
        }
    });

    /*
        Add some path variables for use in the task files
        --------------------------------------------------------------------
    */
    config.wpThemeDir = config.package.wpThemeDir + config.package.wpTheme + '/';
    config.cssDir = config.wpThemeDir + 'css/';
    config.sassDir = config.wpThemeDir + 'sass/';
    config.jsDir = config.wpThemeDir + 'js/';
    config.jsBuiltDir = config.wpThemeDir + 'js-built/';
    config.viewsDir = config.wpThemeDir + 'views/';
    config.imagesDir = config.wpThemeDir + 'images/';
    config.iconDir = config.imagesDir + 'icons/';
    config.iconUrl = config.iconDir.replace('web', '');
    config.uploadsDir = 'web/content/uploads/';
    config.bowerDir = 'web/bower_components/';

    /*
        Init config
        --------------------------------------------------------------------
    */
    grunt.initConfig(config);

    /*
        load our custom tasks
        --------------------------------------------------------------------
    */
    grunt.loadTasks('grunt/tasks/release/tasks');

    /*
        Setup task groups
        --------------------------------------------------------------------
    */
    grunt.registerTask('svg', [
        'grunticon', // handles loading svg and fallbacks
    ]);

    grunt.registerTask('build', [
        'sass', // our preprocessor of choice (libsass)
        'postcss', // postprocessing
        'cssmin',
        'shell:browserify', // js modules
        'shell:uglify', // obfuscate js
        'svg',
        'release' // for cache busting
    ]);

    // Default task(s).
    grunt.registerTask('default', ['build']);

};