module.exports = {
    dist: {
        options: {
            config: 'config.rb',
            sassDir: '<%= wpThemeDir %>sass',
            cssDir: '<%= cssDir %>',
            imagesDir: '<%= wpThemeDir %>images',
            javascriptsDir: '<%= wpThemeDir %>js',
            fontsDir: '<%= wpThemeDir %>fonts',
            outputStyle: 'expanded',
            force: true,
            relativeAssets: true
        }
    }
};