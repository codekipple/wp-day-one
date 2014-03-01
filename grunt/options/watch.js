module.exports = {
    options: {
        livereload: true,
    },
    css: {
        files: ['<%= wpThemeDir %>sass/**/*.scss'],
        tasks: ['sass', 'autoprefixer']
    },
    twig: {
        files: ['<%= wpThemeDir %>views/**/*.twig']
    },
    js: {
        files: ['<%= jsDir %>**/*.js', '!<%= jsDir %>*-pkg.js'],
        tasks: ['browserify']
    }
};