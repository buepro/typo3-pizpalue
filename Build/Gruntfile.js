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
                        resources: '<%= paths.exts.ttAddress.root %>GoogleMap/Resources/',
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
            addressmap: {
                src: '<%= paths.exts.ttAddress.googleMap.js %>Src/jquery.googlemap.js',
                dest: '<%= paths.exts.ttAddress.googleMap.js %>Dist/jquery.googlemap.min.js'
            },
            fastmenu: {
                src: '<%= paths.js %>Src/jquery.fastmenu.js',
                dest: '<%= paths.js %>Dist/jquery.fastmenu.min.js'
            },
            picoverlayBar: {
                src: '<%= paths.js %>Src/ce.picoverlay.bar.js',
                dest: '<%= paths.js %>Dist/ce.picoverlay.bar.min.js'
            },
            picoverlayInfo: {
                src: '<%= paths.js %>Src/ce.picoverlay.info.js',
                dest: '<%= paths.js %>Dist/ce.picoverlay.info.min.js'
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
