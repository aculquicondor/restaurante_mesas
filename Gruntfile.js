module.exports = function(grunt) {
    grunt.initConfig({
        env: {
            options: {},
            debug: {
                LOCAL: 'YES',
                UGLIFY: 'NO'
            },
            dev: {
                LOCAL: 'YES',
                UGLIFY: 'YES'
            },
            prod: {
                LOCAL: 'NO',
                UGLIFY: 'YES'
            }
        },
        uglify: {
            /* Useful options for debugging uglified file
            options: {
                compress: false,
                beautify: true
            },
            */
            all_js: {
                files: {
                    'web/app/js/all.js': [
                        'web/app/js/main.js',
                        'web/app/js/controllers.js',
                        'web/app/js/controllers/*.js',
                        'web/app/js/services.js',
                        'web/app/js/services/*.js',
                        'web/app/js/filters.js'
                    ]
                }
            }
        },
        preprocess: {
            html: {
                src: 'web/app/app.html',
                dest: 'web/app/index.html'
            }
        }
    });

    grunt.loadNpmTasks('grunt-env');
    grunt.loadNpmTasks('grunt-preprocess');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('debug', ['env:debug', 'preprocess']);
    grunt.registerTask('dev', ['env:dev', 'uglify', 'preprocess']);
    grunt.registerTask('prod', ['env:prod', 'uglify', 'preprocess']);
    grunt.registerTask('default', ['dev']);

};
