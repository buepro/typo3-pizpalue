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
            icons: '<%= paths.resources %>Public/Icons/',
            fonts: '<%= paths.resources %>Public/Fonts/',
            js: '<%= paths.resources %>Public/JavaScript/',
            exts: {
                ttAddress: {
                    root: '<%= paths.root %>Extensions/tt_address/',
                    googleMap: {
                        resources: '<%= paths.exts.ttAddress.root %>DisplayMode/GoogleMap/Resources/',
                        js: '<%= paths.exts.ttAddress.googleMap.resources %>Public/JavaScript/'
                    }
                }
            }
        },
        cssmin: {
            pizpalueicon: {
                src: '<%= paths.fonts %>pizpalueicon.css',
                dest: '<%= paths.fonts %>pizpalueicon.min.css'
            }
        },
        uglify: {
            options: {
                warnings: false,
                output: {
                    comments: false
                }
            },
            general: {
                src: '<%= paths.js %>Src/pizpalue.general.js',
                dest: '<%= paths.js %>Dist/pizpalue.general.min.js'
            },
            smoothscroll: {
                src: '<%= paths.js %>Src/pizpalue.smoothscroll.js',
                dest: '<%= paths.js %>Dist/pizpalue.smoothscroll.min.js'
            },
            cookieconsentservice: {
                src: '<%= paths.js %>Src/pizpalue.cookieconsentservice.js',
                dest: '<%= paths.js %>Dist/pizpalue.cookieconsentservice.min.js'
            },
            revealfooterservice: {
                src: '<%= paths.js %>Src/pizpalue.revealfooterservice.js',
                dest: '<%= paths.js %>Dist/pizpalue.revealfooterservice.min.js'
            },
            addressmap: {
                src: '<%= paths.exts.ttAddress.googleMap.js %>Src/jquery.googlemap.js',
                dest: '<%= paths.exts.ttAddress.googleMap.js %>Dist/jquery.googlemap.min.js'
            },
            fastmenu: {
                src: '<%= paths.js %>Src/pizpalue.fastmenu.js',
                dest: '<%= paths.js %>Dist/pizpalue.fastmenu.min.js'
            },
            picoverlayBar: {
                src: '<%= paths.js %>Src/pizpalue.picoverlay.bar.js',
                dest: '<%= paths.js %>Dist/pizpalue.picoverlay.bar.min.js'
            },
            picoverlayInfo: {
                src: '<%= paths.js %>Src/pizpalue.picoverlay.info.js',
                dest: '<%= paths.js %>Dist/pizpalue.picoverlay.info.min.js'
            },
        },
        watch: {
            general: {
                files: '<%= paths.js %>Src/pizpalue.general.js',
                tasks: 'uglify:general'
            },
            smoothscroll: {
                files: '<%= paths.js %>Src/pizpalue.smoothscroll.js',
                tasks: 'uglify:smoothscroll'
            },
            cookieconsentservice: {
                files: '<%= paths.js %>Src/pizpalue.cookieconsentservice.js',
                tasks: 'uglify:cookieconsentservice'
            },
            revealfooterservice: {
                files: '<%= paths.js %>Src/pizpalue.revealfooterservice.js',
                tasks: 'uglify:revealfooterservice'
            },

        },
        webfont: {
            glyphicon: {
                src: '<%= paths.icons %>PizpalueIcon/*.svg',
                dest: '<%= paths.fonts %>',
                options: {
                    font: 'pizpalueicon',
                    template: 'templates/font.css',
                    fontFamilyName: 'PizpalueIcon',
                    engine: 'node',
                    autoHint: false,
                    htmlDemo: false,
                    templateOptions: {
                        baseClass: 'ppicon',
                        classPrefix: 'ppicon-'
                    }
                }
            }
        }
    });

    /**
     * Register tasks
     */
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-webfonts');

    /**
     * Grunt update task
     */
    grunt.registerTask('css', ['cssmin']);
    grunt.registerTask('js', ['uglify']);
    grunt.registerTask('build', ['webfont', 'css', 'js']);
    grunt.registerTask('default', ['build']);

};
