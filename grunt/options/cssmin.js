module.exports = {
    dist: {
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