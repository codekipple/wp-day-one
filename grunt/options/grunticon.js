module.exports = {
    myIcons: {
        files: [{
            expand: true,
            cwd: '<%= iconDir %>min',
            src: ['*.svg', '*.png', '!._'],
            dest: "<%= iconDir %>grunticon"
        }],
        options: {
            pngpath: '<%= iconUrl %>grunticon/png/'
        }
    }
}