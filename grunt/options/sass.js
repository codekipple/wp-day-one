module.exports = {
    // options: {
    //     includePaths: [
    //         'path/to/imports/'
    //     ]
    // },

    dist: {
        files: [
            {
                expand: true,
                flatten: true,
                ext: '.css',
                src: '<%= sassDir %>*.scss',
                dest: '<%= cssDir %>'
            }
        ]
    }
};