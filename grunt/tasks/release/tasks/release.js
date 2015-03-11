module.exports = function(grunt) {
    grunt.registerTask('release', 'Creates a release timestamp', function() {
        var release = {};
        release.path = Date.now();
        grunt.file.write('release.js', JSON.stringify(release));
    });
};