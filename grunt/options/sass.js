module.exports = {

    dist: {
        // options: {
        //     includePaths: [
        //         'path/to/imports/'
        //     ]
        // },
        files: {
            '<%= cssDir %>main.css': '<%= wpThemeDir %>sass/main.scss'
        }
    }

};