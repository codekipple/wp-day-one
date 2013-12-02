module.exports = {
    options: {
        livereload: true,
    },
    scss: {
        files: '<%= wpThemeDir %>sass/**/*.scss',
        tasks: ['compass', 'autoprefixer']
    }
};