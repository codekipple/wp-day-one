module.exports = {
    dist: {
        options: {
            /*
                Setting cache to false is currently needed in version 0.5.0
                https://github.com/gruntjs/grunt-contrib-imagemin/issues/140
            */
            cache: false
        },
        files: [
            {
                expand: true,
                cwd: '<%= imagesDir %>',
                src: '**/*.{png,jpg,gif}',
                dest: '<%= imagesDir %>'
            },
            {
                expand: true,
                cwd: '<%= uploadsDir %>',
                src: '**/*.{png,jpg,gif}',
                dest: '<%= uploadsDir %>'
            }
        ]
    }
};