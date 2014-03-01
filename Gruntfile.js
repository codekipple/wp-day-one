module.exports = function(grunt) {

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

    // add some path variables for use in task options
    config.wpThemeDir = config.package.wpThemeDir + config.package.wpTheme + '/';
    config.cssDir = config.wpThemeDir + 'css/';
    config.jsDir = config.wpThemeDir + 'js/';
    config.jsBuiltDir = config.wpThemeDir + 'js-built/';
    config.imagesDir = config.wpThemeDir + 'images/';
    config.uploadsDir = 'web/content/uploads/';
    config.bowerDir = 'web/bower_components/';

    // init config
    grunt.initConfig(config);

    // task groups.
    grunt.registerTask('optimise',[
        'imagemin',
        'svgmin'
    ]);

    grunt.registerTask('build', [
        'sass',
        'autoprefixer',
        'cssmin',
        'browserify',
        'uglify'
    ]);

    // Default task(s).
    grunt.registerTask('default', ['build']);

};