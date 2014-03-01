module.exports = {
    dist: {
        files: [
            {
                expand: true,
                cwd: '<%= imagesDir %>',
                src: '**/*.svg',
                dest: '<%= imagesDir %>'
            },
            {
                expand: true,
                cwd: '<%= uploadsDir %>',
                src: '**/*.svg',
                dest: '<%= uploadsDir %>'
            }
        ]
    }
};