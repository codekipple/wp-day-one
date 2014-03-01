module.exports = {
    options: {
        noParse: [
            '<%= bowerDir %>**/*.js',
            '<%= jsDir %>vendor/**/*.js'
        ]
    },
    main: {
        options: {
            shim: {
                jquery: {
                    path: '<%= bowerDir %>jquery/dist/jquery.min.js',
                    exports: '$'
                }
            },
        },
        files: {
            '<%= jsDir %>main-pkg.js': ['<%= jsDir %>main.js']
        }
    }
};