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
            doc: '<%= paths.root %>Documentation/'
        },
        cssmin: {
            ucicon: {
                src: '<%= paths.fonts %>ucicon.css',
                dest: '<%= paths.fonts %>ucicon.min.css'
            }
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
        },
        watch: {
            general: {
                files: '<%= paths.js %>Src/jquery.general.js',
                tasks: 'uglify:general'
            },
        },
        webfont: {
            ucicon: {
                src: '<%= paths.icons %>Font/*.svg',
                dest: '<%= paths.fonts %>',
                options: {
                    font: 'ucicon',
                    template: 'templates/font.css',
                    fontFamilyName: 'UcIcon',
                    engine: 'node',
                    autoHint: false,
                    htmlDemo: false,
                    templateOptions: {
                        baseClass: 'ucicon',
                        classPrefix: 'ucicon-'
                    }
                }
            }
        },
        /**
         * @see https://github.com/ericmatthys/grunt-changelog
         * @alternative https://github.com/rafinskipg/git-changelog
         */
        changelog: {
            sample: {
                options: {
                    fileHeader: '# Changelog',
                    after: '2018-12-01',
                    before: '2030-01-01',
                    dest: '<%= paths.doc %>Changelog.md',
                    logArguments: [
                        'master..',
                        '--pretty=%ci %s (Commit %h by %an)',
                        '--no-merges',
                        '--abbrev-commit'
                    ],
                    template: '{{> features}}',
                    featureRegex: /^(.*)$/gim,
                    partials: {
                        features: '{{#if features}}{{#each features}}{{> feature}}{{/each}}{{else}}{{> empty}}{{/if}}\n',
                        feature: '- {{this}} {{this.date}}\n'
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
    grunt.loadNpmTasks('grunt-webfont');
    grunt.loadNpmTasks('grunt-changelog');

    /**
     * Grunt update task
     */
    grunt.registerTask('css', ['cssmin']);
    grunt.registerTask('js', ['uglify']);
    grunt.registerTask('doc', ['changelog']);
    grunt.registerTask('iconfont', ['webfont','css']);
    grunt.registerTask('build', ['webfont','css','js','doc']);
    grunt.registerTask('default', ['build']);

};
