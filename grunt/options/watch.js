var files = {
    css: '<%= sassDir %>**/*.scss',
    js: '<%= jsDir %>**/*.js',
    jsexclude: '!<%= jsDir %>*-pkg.js',
    html: '<%= viewsDir %>**/*.twig'
}

module.exports = {
    options: {
        livereload: true,
    },
    css: {
        files: [files.css],
        tasks: ['sass', 'postcss']
    },
    twig: {
        files: [files.html]
    },
    js: {
        files: [files.js, files.jsexclude],
        tasks: ['shell:browserify']
    }
};