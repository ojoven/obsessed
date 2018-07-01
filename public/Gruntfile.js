// Sample project configuration.
module.exports = function(grunt) {

    grunt.initConfig({
        concat: {
            dist: {
                src: [
                    'js/src/vendor/jquery.min.js',
                    'js/src/vendor/bootstrap.min.js',
                    'js/src/vendor/one.nav.js',
                    'js/src/vendor/picturefill.min.js',
                    'js/src/vendor/popper.min.js',
                    'js/src/vendor/scrollreveal.min.js',
                    'js/src/vendor/slick.min.js',
                    'js/src/app/**/*.js'
                ],
                dest: 'js/app.min.js'
            }
        },
        uglify: {
            my_target : {
                options : {
                    sourceMap : false,
                    sourceMapName : 'sourceMap.map'
                },
                // We'll be using a common JS for all the sites
                files : {
                    'js/app.min.js' : [
                        'js/app.min.js'
                    ]
                }
            }
        },
        compass: {
            dev: {
                dist: {
                    options: {
                        sassDir: 'css/scss',
                        cssDir: 'css',
                        outputStyle: 'nested'
                    }
                }
            },
            prod: {
                dist: {
                    options: {
                        sassDir: 'css/scss',
                        cssDir: 'css',
                        outputStyle: 'nested'
                    }
                }
            }
        },
        watch: {
            watch_js_files: {
                files : ['js/src/**/*.js'],
                tasks : ['concat']
            },
            watch_css_files: {
                files : ['css/scss/**/*.scss'],
                tasks : ['compass:dev']
            }
        },
        majestic_updateversions: { css: {}, js: {} }
    });

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-compass');

    // Default, to be used on development environments
    grunt.registerTask('default', ['compass:dev', 'concat', 'watch']); // First we compile and concat JS and then we watch

    // Post Commit, to be executed after commit
    grunt.registerTask('deploy', ['concat', 'uglify', 'compass:prod']);

};