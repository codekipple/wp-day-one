module.exports = {
    dist: {
        options: {
            browsers: ['last 2 versions', '> 1%', 'ie 8', 'ff 17', 'opera 12.1']
        },
        files: [
            {
                cwd : '<%= cssDir %>',
                dest : '<%= cssDir %>',
                src : '*.css',
                ext : '.css',
                expand : true
            }
        ]
    }
};