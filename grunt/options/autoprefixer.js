module.exports = {
    options: {
        browsers: ['last 2 versions', '> 1%', 'ie 8', 'ff 17', 'opera 12.1']
    },
    multiple_files: {
        expand : true,
        flatten: true,
        src : '<%= cssDir %>/**/*.css',
        dest : '<%= cssDir %>',
    }
};