module.exports = {
    dist: {
        files: [
            {
                expand : true,
                cwd : '<%= cssDir %>',
                dest : '<%= cssDir %>',
                src : '*.css',
                ext : '.css',
            }
        ]
    }
};