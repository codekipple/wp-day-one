module.exports = {
    dist: {
        files: [
            {
                expand : true,
                cwd : '<%= jsDir %>',
                dest : '<%= jsBuiltDir %>',
                src : ['**/*.js', '!vendor/selectivizr.js'],
                ext : '.js',
            }
        ]
    }
};