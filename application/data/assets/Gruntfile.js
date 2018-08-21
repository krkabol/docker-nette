
module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        bower_concat: {
            all: {
                dest: {
                    'js': 'js_vendor/lib.js',
                    'css': 'css_vendor/lib.css'
                }
            }
        },

        concat_css: {
            options: {
                // Task-specific options go here.
            },
            app: {
                src: ["css/*.css"],
                dest: "../htdocs/www/css/app.css"
            },
            lib: {
                src: ["css_vendor/*.css"],
                dest: "../htdocs/www/css/lib.css"
            },
        },

        babel: {
            options: {
                "sourceMap": true,
                "presets": ['es2015-without-strict']
            },
            dist: {
                files: [{
                    "expand": true,
                    "cwd": "js",
                    "src": ["*.js"], //["**/*.js"],
                    "dest": "js/compiled/",
                    "ext": "-compiled.js"
                }]
            }
        },

        uglify: {
            options: {
                manage: false,
                beautify: false,
                preserveComments: false //'all' = > preserve all comments on JS files
            },
            my_target: {
                files: {
                    '../htdocs/www/js/app.min.js': ['js/compiled/*-compiled.js'],
                    '../htdocs/www/js/lib.min.js': ['js_vendor/*.js']
                }
            }
        },

        watch: {

            files: ['js/*.js', 'css/*.css', '!*.min.css'],
            tasks: ['uglify', 'concat_css', 'cssmin']

        },


        cssmin: {
            my_target: {
                files: [{
                    expand: true,
                    cwd: '../htdocs/www/css/',
                    src: ['*.css', '!*.min.css'],
                    dest: '../htdocs/www/css/',
                    ext: '.min.css'

                }]
            }
        }

    });

    //  npm install grunt-contrib-sass --save-dev

    require('load-grunt-tasks')(grunt);

    grunt.registerTask('default', ['bower_concat','babel','uglify', 'concat_css', 'cssmin']);
}
;