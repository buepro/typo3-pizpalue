module.exports = function(grunt) {

    /**
     * Project configuration.
     */
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        paths: {
            root: '../',
            node: 'node_modules/',
            resources: '<%= paths.root %>Resources/',
            js: '<%= paths.resources %>Public/JavaScript/'
        },
        uglify: {
            options: {
                compress: {
                    warnings: false
                },
                output: {
                    comments: false
                }
            },
            general: {
                src: '<%= paths.js %>Src/jquery.general.js',
                dest: '<%= paths.js %>Dist/jquery.general.min.js'
            },
            smoothscroll: {
                src: '<%= paths.js %>Src/jquery.smoothscroll.js',
                dest: '<%= paths.js %>Dist/jquery.smoothscroll.min.js'
            },
            cookieconsentservice: {
                src: '<%= paths.js %>Src/jquery.cookieconsentservice.js',
                dest: '<%= paths.js %>Dist/jquery.cookieconsentservice.min.js'
            },
            revealfooterservice: {
                src: '<%= paths.js %>Src/jquery.revealfooterservice.js',
                dest: '<%= paths.js %>Dist/jquery.revealfooterservice.min.js'
            },
        },
        watch: {
            general: {
                files: '<%= paths.js %>Src/jquery.general.js',
                tasks: 'uglify:general'
            },
            smoothscroll: {
                files: '<%= paths.js %>Src/jquery.smoothscroll.js',
                tasks: 'uglify:smoothscroll'
            },
            cookieconsentservice: {
                files: '<%= paths.js %>Src/jquery.cookieconsentservice.js',
                tasks: 'uglify:cookieconsentservice'
            },
            revealfooterservice: {
                files: '<%= paths.js %>Src/jquery.revealfooterservice.js',
                tasks: 'uglify:revealfooterservice'
            },

        },
    });

    /**
     * Register tasks
     */
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    /**
     * Grunt update task
     */
    grunt.registerTask('js', ['uglify']);
    grunt.registerTask('default', ['js']);

};
