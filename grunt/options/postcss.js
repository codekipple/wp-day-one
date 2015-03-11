module.exports = {
    options: {
        processors: [
            require('autoprefixer-core')({browsers: ['last 2 versions', '> 1%', 'ie 8', 'ff 17', 'opera 12.1']}).postcss
        ]
    },
    dist: {
        expand : true,
        flatten: true,
        src : '<%= cssDir %>/**/*.css',
        dest : '<%= cssDir %>',
    }
};