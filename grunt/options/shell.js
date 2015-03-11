module.exports = {
    options: {
        stderr: false
    },

    browserify: {
        command: 'node_modules/.bin/browserify <%= jsDir %>main.js > <%= jsDir %>main-pkg.js'
    },

    uglify: {
        command: 'node_modules/.bin/uglifyjs <%= jsDir %>main-pkg.js > <%= jsBuiltDir %>main-pkg.js'
    }

};